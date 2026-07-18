<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // -------------------------------------------------------
    // Public: Store order from checkout form
    // -------------------------------------------------------

    public function store(Request $request)
    {
        if (! Setting::allowsOnlineCheckout()) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب الأونلاين غير مفعّل حاليًا. تواصلي عبر واتساب.',
            ], 403);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'governorate' => 'required|string|max:100',
            'address' => 'required|string',
            'notes' => 'nullable|string',
            'meta_event_id' => 'nullable|string|max:120',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.image' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number'    => Order::generateOrderNumber(),
                'customer_name'   => $validated['customer_name'],
                'customer_phone'  => $validated['customer_phone'],
                'governorate'     => $validated['governorate'],
                'address'         => $validated['address'],
                'notes'           => $validated['notes'] ?? null,
                'total_amount'    => collect($validated['items'])->sum(fn($i) => $i['price'] * $i['quantity']),
                'status'          => 'new',
            ]);

            foreach ($validated['items'] as $item) {
                $order->items()->create([
                    'product_name'  => $item['name'],
                    'price'         => $item['price'],
                    'quantity'      => $item['quantity'],
                    'subtotal'      => $item['price'] * $item['quantity'],
                    'product_image' => $item['image'] ?? null,
                ]);
            }

            DB::commit();

            $request->session()->put('completed_order_' . $order->order_number, true);

            // ── Meta Conversions API: Server-side Purchase (Disabled per client requirements to run only on Thank You Page) ──
            // $eventId = $validated['meta_event_id'] ?? ('Purchase_' . $order->order_number);
            // app(\App\Services\MetaConversionsApi::class)->sendPurchase(
            //     request: $request,
            //     order:   $order->load('items'),
            //     eventId: $eventId
            // );

            return response()->json([
                'success'       => true,
                'order_number'  => $order->order_number,
                'thank_you_url' => route('orders.thank-you', ['order' => $order->order_number]),
                'message'       => 'تم استلام طلبك بنجاح! رقم طلبك: ' . $order->order_number,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الطلب. يرجى المحاولة مرة أخرى.',
            ], 500);
        }
    }

    // -------------------------------------------------------
    // Admin: Orders Dashboard
    // -------------------------------------------------------

    public function dashboard(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('governorate')) {
            $query->where('governorate', $request->governorate);
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        // Group by date — today first, then rest grouped by day
        $allOrders = $query->get();

        $today = today()->toDateString();
        $todayOrders = $allOrders->filter(fn($o) => $o->created_at->toDateString() === $today);

        // Group remaining by date (excluding today)
        $groupedByDay = $allOrders
            ->filter(fn($o) => $o->created_at->toDateString() !== $today)
            ->groupBy(fn($o) => $o->created_at->toDateString());

        // Group by month (current month separate)
        $currentMonth = now()->format('Y-m');
        $groupedByMonth = $allOrders
            ->filter(fn($o) => $o->created_at->format('Y-m') !== $currentMonth)
            ->groupBy(fn($o) => $o->created_at->format('Y-m'));

        $currentMonthOrders = $allOrders->filter(fn($o) => $o->created_at->format('Y-m') === $currentMonth);

        $stats = [
            'total'     => Order::count(),
            'new'       => Order::where('status', 'new')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'shipped'   => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'revenue'   => Order::where('status', 'delivered')->sum('total_amount'),
            'today'     => Order::whereDate('created_at', today())->count(),
            'week'      => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        $governorates = Order::distinct()->pluck('governorate')->sort()->values();

        return view('admin.orders.dashboard', compact(
            'allOrders', 'todayOrders', 'groupedByDay',
            'currentMonthOrders', 'groupedByMonth',
            'stats', 'governorates'
        ));
    }

    public function logs()
    {
        $logs = OrderLog::with(['order', 'user'])
            ->latest()
            ->paginate(50);

        return view('admin.orders.logs', compact('logs'));
    }

    // -------------------------------------------------------
    // Admin: Show single order
    // -------------------------------------------------------

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    // -------------------------------------------------------
    // Admin: Drawer (AJAX partial)
    // -------------------------------------------------------

    public function drawer(Order $order)
    {
        $order->load(['items', 'logs.user', 'creator']);
        $user = Auth::user();
        return view('admin.orders.drawer', compact('order', 'user'));
    }

    // -------------------------------------------------------
    // Admin: Update status (with log + permission check)
    // -------------------------------------------------------

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'      => 'required|in:new,confirmed,preparing,shipped,delivered,cancelled,returned',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;

        $data = ['status' => $request->status];

        if ($request->status === 'confirmed' && !$order->confirmed_at) $data['confirmed_at'] = now();
        if ($request->status === 'shipped'   && !$order->shipped_at)   $data['shipped_at']   = now();
        if ($request->status === 'delivered' && !$order->delivered_at) $data['delivered_at'] = now();
        if ($request->filled('admin_notes')) $data['admin_notes'] = $request->admin_notes;

        $order->update($data);

        // ── سجّل من غيّر الحالة ──────────────────────────────
        OrderLog::create([
            'order_id'    => $order->id,
            'user_id'     => Auth::id(),
            'action'      => 'status_changed',
            'description' => "تم تغيير الحالة من [{$oldStatus}] إلى [{$request->status}]",
            'meta'        => [
                'old_status'  => $oldStatus,
                'new_status'  => $request->status,
                'admin_notes' => $request->admin_notes,
                'ip'          => $request->ip(),
            ],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث حالة الطلب بنجاح.']);
        }
        return back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
    public function thankYou($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $shouldTrackPurchase = session()->pull('completed_order_' . $order->order_number, false);

        return view('thank-you', [
            'order'               => $order,
            'orderTotal'          => $order->total_amount,
            'shouldTrackPurchase' => $shouldTrackPurchase,
        ]);
    }

}
