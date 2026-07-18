<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تم استلام طلبك | 3ajeeba</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #f8fafc;
            color: #0f172a;
            font-family: Arial, sans-serif;
        }
        main {
            width: min(92%, 32rem);
            padding: 2.5rem;
            text-align: center;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(15, 23, 42, .08);
        }
        h1 { margin: 0 0 1rem; }
        .number { margin: 1.5rem 0; font-size: 1.25rem; font-weight: 700; }
        a { color: #2563eb; }
    </style>
</head>
<body>
    <main>
        <h1>تم استلام طلبك بنجاح</h1>
        <p class="number">رقم الطلب: {{ $order->order_number }}</p>
        <a href="{{ route('home') }}">العودة للرئيسية</a>
    </main>
</body>
</html>
