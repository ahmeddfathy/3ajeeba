<script>
let activeRow = null;

function toggleGroup(id) {
    const body    = document.getElementById('group-' + id);
    const chevron = document.getElementById('chevron-' + id);
    if (!body) return;
    const isOpen = body.style.display !== 'none';
    body.style.display = isOpen ? 'none' : '';
    if (chevron) chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
}

function toggleTableMonth(monthKey) {
    const rows = document.querySelectorAll('.orders-table tbody tr');
    let isMonthClosed = null;
    
    rows.forEach(row => {
        if (row.classList.contains('table-group-month-row') && row.getAttribute('data-month') === monthKey) {
            const chevron = row.querySelector('.group-chevron');
            isMonthClosed = chevron.style.transform === 'rotate(-90deg)';
            chevron.style.transform = isMonthClosed ? 'rotate(0deg)' : 'rotate(-90deg)';
            return;
        }
        
        if (row.getAttribute('data-month') === monthKey) {
            row.style.display = isMonthClosed ? '' : 'none';
        }
    });
}

function toggleTableDay(dayKey) {
    const rows = document.querySelectorAll('.orders-table tbody tr');
    let isDayClosed = null;
    
    rows.forEach(row => {
        if (row.classList.contains('table-group-day-row') && row.getAttribute('data-day') === dayKey) {
            const chevron = row.querySelector('.group-chevron');
            isDayClosed = chevron.style.transform === 'rotate(-90deg)';
            chevron.style.transform = isDayClosed ? 'rotate(0deg)' : 'rotate(-90deg)';
            return;
        }
        
        if (row.getAttribute('data-day') === dayKey && !row.classList.contains('table-group-day-row')) {
            row.style.display = isDayClosed ? '' : 'none';
        }
    });
}

function switchView(view) {
    document.getElementById('tableView').style.display  = view === 'table'  ? '' : 'none';
    document.getElementById('kanbanView').style.display = view === 'kanban' ? '' : 'none';
    document.getElementById('btnTable').classList.toggle('active',  view === 'table');
    document.getElementById('btnKanban').classList.toggle('active', view === 'kanban');
    localStorage.setItem('ordersView', view);
}

// restore last view
const savedView = localStorage.getItem('ordersView');
if (savedView === 'kanban') switchView('kanban');

function openDrawer(orderId) {
    // highlight row
    document.querySelectorAll('.orders-table tbody tr').forEach(r => r.classList.remove('active-row'));
    const row = document.querySelector(`tr[data-id="${orderId}"]`);
    if (row) row.classList.add('active-row');
    activeRow = row;

    // show overlay + loading
    document.getElementById('drawerOverlay').classList.add('open');
    document.getElementById('drawerBody').innerHTML = `
        <div class="drawer-loading">
            <div class="spinner"></div>
            <span>جاري التحميل...</span>
        </div>`;

    fetch(`/admin/orders/${orderId}/drawer`)
        .then(r => r.text())
        .then(html => {
            document.getElementById('drawerBody').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('drawerBody').innerHTML = `<div class="drawer-loading"><i class="bi bi-exclamation-circle" style="font-size:2rem;color:#dc2626"></i><span>حدث خطأ في التحميل</span></div>`;
        });
}

function closeDrawer(e) {
    if (e && e.target !== document.getElementById('drawerOverlay')) return;
    document.getElementById('drawerOverlay').classList.remove('open');
    if (activeRow) { activeRow.classList.remove('active-row'); activeRow = null; }
}

// ESC key
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

// Handle status update via AJAX without refresh
document.addEventListener('submit', function(e) {
    const form = e.target;
    // Check if it's an update status form
    if (form.action && form.action.includes('/status')) {
        e.preventDefault();
        
        const btn = form.querySelector('button[type="submit"]');
        const oldHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:1rem;height:1rem" role="status" aria-hidden="true"></span>';
            btn.disabled = true;
        }

        fetch(form.action, {
            method: form.method,
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Fetch updated HTML
                fetch(window.location.href)
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Update table and kanban views
                    document.getElementById('tableView').innerHTML = doc.getElementById('tableView').innerHTML;
                    document.getElementById('kanbanView').innerHTML = doc.getElementById('kanbanView').innerHTML;
                    
                    // Update drawer if it's currently open
                    const drawer = document.getElementById('drawerOverlay');
                    if (drawer.classList.contains('open') && activeRow) {
                        const orderId = activeRow.dataset.id;
                        openDrawer(orderId);
                    }
                });
            }
        })
        .finally(() => {
            if (btn) {
                btn.innerHTML = oldHtml;
                btn.disabled = false;
            }
        });
    }
});
</script>
