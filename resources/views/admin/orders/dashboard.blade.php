@extends('layouts.admin')

@section('title', 'إدارة الطلبات')
@section('page-title', 'إدارة الطلبات')

@push('styles')
<style>
/* ====== STAT CARDS ====== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--card);
    border-radius: 16px;
    padding: 22px 20px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform .2s, box-shadow .2s;
    text-decoration: none;
    color: inherit;
}
.stat-card:hover { color: inherit; }
.stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
.stat-icon.blue    { background: #eff6ff; color: #2563eb; }
.stat-icon.purple  { background: var(--purple-100); color: var(--purple-600); }
.stat-icon.orange  { background: #fff7ed; color: #ea580c; }
.stat-icon.indigo  { background: #eef2ff; color: #4f46e5; }
.stat-icon.green   { background: #f0fdf4; color: #16a34a; }
.stat-icon.gold    { background: #fffbeb; color: var(--gold-500); }
.stat-value { font-size: 1.75rem; font-weight: 900; line-height: 1; margin-bottom: 5px; }
.stat-label { font-size: 0.82rem; color: var(--muted); font-weight: 600; }

@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

/* ====== FILTERS ====== */
.filters-bar {
    background: var(--card);
    border-radius: 14px;
    padding: 16px 20px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    margin-bottom: 20px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: flex-end;
}
.filter-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 140px; }
.filter-label { font-size: 0.72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }

/* Custom Select */
.custom-select-wrap { position: relative; }
.custom-select-wrap select {
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 9px 36px 9px 14px;
    font-family: 'Cairo', sans-serif;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text);
    background: #fff;
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.custom-select-wrap select:focus {
    border-color: var(--purple-500);
    box-shadow: 0 0 0 3px rgba(124,77,204,0.1);
}
.custom-select-wrap select:hover { border-color: var(--purple-300); }
.custom-select-wrap .select-arrow {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--purple-400);
    font-size: 0.75rem;
    pointer-events: none;
}

/* Search Input */
.filter-search-wrap { position: relative; }
.filter-search-wrap input {
    width: 100%;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 9px 14px;
    font-family: 'Cairo', sans-serif;
    font-size: 0.875rem;
    color: var(--text);
    background: #fff;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.filter-search-wrap input:focus {
    border-color: var(--purple-500);
    box-shadow: 0 0 0 3px rgba(124,77,204,0.1);
}
.filter-search-wrap .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 0.85rem;
    pointer-events: none;
}

.btn-filter {
    background: linear-gradient(135deg, var(--purple-700), var(--purple-500));
    color: white;
    border: none;
    border-radius: 10px;
    padding: 10px 22px;
    font-family: 'Cairo', sans-serif;
    font-weight: 700;
    font-size: 0.875rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: opacity .2s, transform .2s;
    align-self: flex-end;
    box-shadow: 0 4px 12px rgba(107,61,173,.25);
    white-space: nowrap;
}
.btn-filter:hover { opacity: .9; transform: translateY(-1px); }

.btn-reset {
    background: #fef2f2;
    color: #dc2626;
    border: 1.5px solid #fecaca;
    border-radius: 10px;
    padding: 10px 16px;
    font-family: 'Cairo', sans-serif;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    align-self: flex-end;
    transition: all .2s;
    white-space: nowrap;
}
.btn-reset:hover { background: #dc2626; color: white; border-color: #dc2626; }

/* ====== ORDERS TABLE ====== */
.orders-card { background: var(--card); border-radius: 14px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; }
.orders-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.orders-table thead { position: sticky; top: 0; z-index: 2; }
.orders-table th { background: var(--purple-900); color: rgba(255,255,255,0.85); font-weight: 700; font-size: 0.76rem; padding: 14px 16px; text-align: right; border-bottom: none; white-space: nowrap; text-transform: uppercase; letter-spacing: 0.5px; }
.orders-table td { padding: 14px 16px; border-bottom: 1px solid #f0eef4; font-size: 0.88rem; vertical-align: middle; }
.orders-table tbody tr { transition: background .15s; cursor: pointer; }
.orders-table tbody tr:last-child td { border-bottom: none; }
.orders-table tbody tr:hover td { background: #faf9fd; }
.orders-table tbody tr.active-row td { background: var(--purple-50); }
.order-number { font-weight: 800; color: var(--purple-600); font-size: 0.85rem; }
.customer-name { font-weight: 700; color: var(--text); }
.customer-phone { color: var(--muted); font-size: 0.8rem; direction: ltr; text-align: right; margin-top: 2px; }
.total-cell { font-weight: 800; color: var(--text); font-size: 0.92rem; }
.total-cell .currency { color: var(--muted); font-size: 0.78rem; font-weight: 600; }
.date-cell { color: #5a5370; font-size: 0.82rem; white-space: nowrap; line-height: 1.6; }
.date-cell .time { color: var(--muted); font-size: 0.75rem; }

/* ====== STATUS BADGES ====== */
.status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; white-space: nowrap; }
.badge-new       { background:#eff6ff; color:#1d4ed8; }
.badge-confirmed { background:var(--purple-100); color:var(--purple-700); }
.badge-preparing { background:#fff7ed; color:#c2410c; }
.badge-shipped   { background:#eef2ff; color:#4338ca; }
.badge-delivered { background:#f0fdf4; color:#15803d; }
.badge-cancelled { background:#fef2f2; color:#b91c1c; }
.badge-returned  { background:#f9fafb; color:#6b7280; }

/* ====== ACTION BUTTONS ====== */
.actions-cell { display: flex; gap: 6px; }
.action-btn { width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border); background: white; color: var(--muted); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; font-size: 0.85rem; transition: all .2s; }
.action-btn.advance-btn { color: var(--purple-600); border-color: var(--purple-200); }
.action-btn.advance-btn:hover { background: var(--purple-600); color: white; border-color: var(--purple-600); }
.action-btn.danger:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

/* ====== PAGINATION ====== */
.pagination-wrap { padding: 16px 20px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
.pagination-info { color: var(--muted); font-size: 0.83rem; font-weight: 600; }

/* ====== VIEW SWITCHER ====== */
.view-switcher {
    display: flex;
    gap: 6px;
    margin-bottom: 16px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 5px;
    width: fit-content;
    box-shadow: var(--shadow);
}
.view-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 7px 18px;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: var(--muted);
    font-family: 'Cairo', sans-serif;
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
}
.view-btn.active {
    background: linear-gradient(135deg, var(--purple-700), var(--purple-500));
    color: #fff;
    box-shadow: 0 3px 10px rgba(107,61,173,.3);
}
.view-btn:not(.active):hover { background: var(--purple-50); color: var(--purple-600); }

/* ====== KANBAN ====== */
.kanban-board {
    display: grid;
    gap: 14px;
}
.kanban-row-4 { grid-template-columns: repeat(4, 1fr); }
.kanban-row-3 { grid-template-columns: repeat(3, 1fr); }
.kanban-col {
    background: var(--card);
    border-radius: 14px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
    min-width: 200px;
    display: flex;
    flex-direction: column;
}
.kanban-col-header {
    padding: 9px 12px;
    background: linear-gradient(135deg, var(--purple-900), var(--purple-700));
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}
.kanban-col-title {
    display: flex;
    align-items: center;
    gap: 7px;
    font-weight: 800;
    font-size: 0.85rem;
    color: #fff;
}
.kanban-col-title i { font-size: 1rem; filter: brightness(1.5); }
.kanban-col-count {
    font-size: 0.75rem;
    font-weight: 800;
    padding: 2px 9px;
    border-radius: 20px;
}
.kanban-col-body {
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    height: 450px;
    overflow-y: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.kanban-col-body::-webkit-scrollbar { display: none; }
.kb-month-header {
    background: #f8f7ff;
    padding: 7px 10px;
    border-radius: 8px;
    margin: 10px 0 5px;
    font-size: 0.78rem;
    font-weight: 800;
    color: var(--purple-700);
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    border: 1px solid var(--purple-100);
    position: sticky;
    top: -10px;
    z-index: 5;
}
.kb-day-header {
    background: #fff;
    padding: 5px 8px;
    border-radius: 6px;
    margin: 5px 0;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--muted);
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    border: 1px dashed var(--border);
    margin-right: 10px;
}
.kb-day-body {
    padding-right: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.kanban-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 10px 12px;
    cursor: pointer;
    transition: transform .15s, box-shadow .15s, border-color .15s;
}

.kanban-card-num { font-size: 0.72rem; font-weight: 800; color: var(--purple-600); margin-bottom: 4px; }
.kanban-card-name { font-size: 0.85rem; font-weight: 700; color: var(--text); }
.kanban-card-phone { font-size: 0.75rem; color: var(--muted); direction: ltr; text-align: right; margin-bottom: 8px; }
.kanban-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.kanban-card-total { font-size: 0.82rem; font-weight: 800; color: var(--text); }
.kanban-card-time  { font-size: 0.72rem; color: var(--muted); }
.kanban-advance-btn {
    width: 100%;
    background: var(--purple-50);
    color: var(--purple-700);
    border: 1.5px solid var(--purple-200);
    border-radius: 7px;
    padding: 5px 8px;
    font-family: 'Cairo', sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    transition: all .2s;
}
.kanban-advance-btn:hover { background: var(--purple-600); color: #fff; border-color: var(--purple-600); }
.kanban-empty { text-align: center; padding: 20px; color: var(--purple-200); font-size: 1.5rem; }

/* ====== EMPTY STATE ====== */
.empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
.empty-icon { font-size: 3.5rem; margin-bottom: 16px; opacity: .3; }

/* ====== GROUPED SECTIONS ====== */
.group-section { margin-bottom: 24px; }

.group-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-radius: 16px;
    margin-bottom: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
}
.group-header:hover { background-opacity: 0.95; }

.group-header-left { display: flex; align-items: center; gap: 10px; }

.group-stats { display: flex; align-items: center; gap: 5px; margin: 0 10px; flex-wrap: wrap; }
.stat-pill {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 12px;
    background: rgba(255,255,255,0.12);
    color: #fff;
    display: flex;
    align-items: center;
    gap: 4px;
    border: 1px solid rgba(255,255,255,0.08);
}
.stat-pill i { font-size: 0.75rem; }
.prev-month-header .stat-pill { background: var(--purple-50); color: var(--purple-700); border-color: var(--purple-100); }

.today-header { background: linear-gradient(135deg, var(--purple-800), var(--purple-600)); color: #fff; box-shadow: 0 4px 15px rgba(163,111,80,0.2); }
.month-header  { background: linear-gradient(135deg, var(--purple-700), var(--purple-600)); color: #fff; box-shadow: 0 4px 15px rgba(163,111,80,0.15); }
.prev-month-header { 
    background: #fff; 
    border: 1px solid var(--border); 
    color: var(--text); 
    box-shadow: 0 4px 12px rgba(107,61,173,0.06); 
}
.prev-month-header:hover { border-color: var(--purple-300); background: linear-gradient(to left, #fff, var(--purple-50)); }

.group-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.today-dot { background: var(--gold-400); box-shadow: 0 0 0 3px rgba(240,192,80,.3); }
.month-dot  { background: rgba(255,255,255,.6); }
.prev-dot   { background: var(--purple-300); }

.group-title { font-size: 1rem; font-weight: 800; }
.group-date  { font-size: 0.8rem; opacity: .75; }
.group-count { font-size: 0.8rem; font-weight: 700; background: rgba(255,255,255,.2); padding: 3px 10px; border-radius: 20px; }
.prev-month-header .group-count { background: var(--purple-100); color: var(--purple-700); }

.day-group { margin-bottom: 12px; }
.day-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border: 1px solid var(--border);
    padding: 12px 20px;
    border-radius: 14px;
    margin-top: 10px;
    margin-bottom: 10px;
    box-shadow: 0 2px 6px rgba(107,61,173,0.03);
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--text);
    transition: all .2s;
}
.day-label:hover { border-color: var(--purple-200); background: var(--purple-50); }
.day-count {
    background: var(--purple-100);
    color: var(--purple-700);
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 0.78rem;
}

.group-empty {
    text-align: center;
    padding: 24px;
    color: var(--muted);
    font-size: 0.875rem;
    background: var(--card);
    border-radius: 12px;
    border: 1px dashed var(--border);
}
.empty-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 8px; color: var(--text); }

/* ====== ORDER DRAWER ====== */
.order-drawer-overlay {
    position: fixed;
    inset: 0;
    background: rgba(30,10,60,0.35);
    backdrop-filter: blur(3px);
    z-index: 2000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
}
.order-drawer-overlay.open { opacity: 1; pointer-events: all; }

.order-drawer {
    position: fixed;
    top: 0;
    left: 0;
    width: 480px;
    max-width: 95vw;
    height: 100vh;
    background: var(--bg);
    box-shadow: -8px 0 40px rgba(0,0,0,0.15);
    z-index: 2001;
    display: flex;
    flex-direction: column;
    transform: translateX(-100%);
    transition: transform 0.3s cubic-bezier(.4,0,.2,1);
    overflow: hidden;
}

.order-drawer-overlay.open .order-drawer {
    transform: translateX(0);
}

.drawer-header {
    background: linear-gradient(135deg, var(--purple-900), var(--purple-600));
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.drawer-header h3 {
    color: #fff;
    font-size: 1rem;
    font-weight: 800;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.drawer-close {
    background: rgba(255,255,255,0.15);
    border: none;
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
    flex-shrink: 0;
}
.drawer-close:hover { background: rgba(255,255,255,0.25); }

.drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 1.25rem;
    scrollbar-width: none;
    -ms-overflow-style: none;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
}
.drawer-body::-webkit-scrollbar { display: none; }

/* Drawer inner styles */
.d-card { background: var(--card); border-radius: 14px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 14px; }
.d-card-header { background: linear-gradient(135deg, var(--purple-900), var(--purple-700)); padding: 10px 16px; border-bottom: none; display: flex; align-items: center; gap: 7px; font-weight: 700; color: #fff; font-size: 0.82rem; letter-spacing: 0.02em; }
.d-card-body { padding: 14px 16px; }
.d-info-row { display: flex; align-items: flex-start; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--border); font-size: 0.85rem; }
.d-info-row:last-child { border-bottom: none; }
.d-info-label { color: var(--muted); font-weight: 600; min-width: 100px; flex-shrink: 0; font-size: 0.8rem; }
.d-info-value { font-weight: 600; }

.d-order-num { font-size: 1.3rem; font-weight: 900; color: #fff; }
.d-order-meta { font-size: 0.78rem; color: rgba(255,255,255,0.7); margin-top: 2px; }

.items-mini-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
.items-mini-table th { background: var(--purple-50); color: var(--purple-700); font-weight: 700; padding: 8px 12px; text-align: right; font-size: 0.75rem; }
.items-mini-table td { padding: 9px 12px; border-bottom: 1px solid var(--border); }
.items-mini-table tr:last-child td { border-bottom: none; }
.items-mini-table .total-row { background: var(--purple-50); font-weight: 800; color: var(--purple-700); }

.mc-select { width: 100%; border: 1px solid var(--border); border-radius: 10px; padding: 9px 12px; font-family: 'Cairo', sans-serif; font-size: 0.88rem; color: var(--text); background: var(--bg); transition: border-color .2s; }
.mc-select:focus { outline: none; border-color: var(--purple-400); background: white; }
.mc-textarea { width: 100%; border: 1px solid var(--border); border-radius: 10px; padding: 9px 12px; font-family: 'Cairo', sans-serif; font-size: 0.85rem; color: var(--text); background: var(--bg); resize: vertical; min-height: 70px; transition: border-color .2s; }
.mc-textarea:focus { outline: none; border-color: var(--purple-400); background: white; }
.btn-update { background: var(--gold-gradient); color: white; border: none; border-radius: 10px; padding: 10px 20px; font-family: 'Cairo', sans-serif; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: flex; align-items: center; gap: 6px; width: 100%; justify-content: center; transition: opacity .2s; }
.btn-update:hover { opacity: .9; }

.workflow-mini { display: flex; align-items: center; gap: 0; overflow-x: auto; padding: 4px 0 8px; }
.wf-step { display: flex; flex-direction: column; align-items: center; gap: 4px; flex: 1; min-width: 60px; position: relative; }
.wf-step:not(:last-child)::after { content: ''; position: absolute; top: 16px; left: 0; width: 100%; height: 2px; background: var(--border); z-index: 0; }
.wf-step.done:not(:last-child)::after { background: var(--purple-500); }
.wf-icon { width: 32px; height: 32px; border-radius: 50%; background: var(--bg); border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: var(--muted); position: relative; z-index: 1; }
.wf-step.done .wf-icon { background: var(--purple-600); border-color: var(--purple-600); color: white; }
.wf-step.current .wf-icon { background: white; border-color: var(--purple-500); color: var(--purple-600); box-shadow: 0 0 0 3px rgba(124,77,204,.2); }
.wf-label { font-size: 0.65rem; font-weight: 700; color: var(--muted); text-align: center; white-space: nowrap; }
.wf-step.done .wf-label, .wf-step.current .wf-label { color: var(--purple-600); }

.drawer-loading { display: flex; align-items: center; justify-content: center; height: 200px; color: var(--muted); flex-direction: column; gap: 12px; }
.spinner { width: 36px; height: 36px; border: 3px solid var(--purple-100); border-top-color: var(--purple-500); border-radius: 50%; animation: spin 0.7s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .order-drawer { width: 100%; }
    
    /* Kanban Mobile Fix */
    .kanban-row-4, .kanban-row-3 {
        grid-template-columns: 1fr !important;
        gap: 10px;
    }
    .kanban-col-body {
        height: auto !important;
    }
}

/* ====== WELCOME BANNER ====== */
.welcome-banner {
    background: #FFFFFF;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 22px 24px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    color: var(--text);
    box-shadow: var(--shadow);
}

.welcome-banner::before {
    display: none;
}

.welcome-banner-content {
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
    z-index: 1;
}

.welcome-icon {
    width: 56px;
    height: 56px;
    background: var(--purple-50);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: var(--purple-600);
    border: 1px solid var(--purple-200);
}

.welcome-title {
    font-size: 1.3rem;
    font-weight: 800;
    margin-bottom: 4px;
    color: var(--text);
}

.welcome-subtitle {
    font-size: 0.9rem;
    color: var(--muted);
    font-weight: 600;
    margin-bottom: 0;
}

.welcome-date {
    background: var(--purple-50);
    color: var(--purple-700);
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid var(--purple-200);
}
/* ====== TABLE GROUPING SEPARATORS ====== */
.table-group-month-cell {
    background: linear-gradient(135deg, var(--purple-700), var(--purple-500)) !important;
    color: #ffffff !important;
    font-weight: 800;
    font-size: 0.95rem;
    padding: 12px 20px !important;
    cursor: pointer !important;
}
.table-group-day-cell {
    background: var(--purple-50) !important;
    color: var(--purple-700) !important;
    font-weight: 700;
    font-size: 0.88rem;
    padding: 10px 20px !important;
    border-bottom: 1.5px dashed #dac6f7 !important;
    cursor: pointer !important;
}
.table-group-month-row:hover td {
    background: linear-gradient(135deg, var(--purple-700), var(--purple-500)) !important;
    color: #ffffff !important;
}
.table-group-day-row:hover td {
    background: #f7f3ff !important;
    color: var(--purple-700) !important;
}
</style>
@endpush

@section('content')

<!-- WELCOME BANNER -->
<div class="welcome-banner">
    <div class="welcome-banner-content">

        <div>
            <h4 class="welcome-title">أهلاً بك، {{ auth()->user()?->name }} 👋</h4>
            <p class="welcome-subtitle">إليك ملخص سريع لحالة الطلبات اليوم في عجيبة</p>
        </div>
    </div>
    <div class="welcome-date">
        <i class="bi bi-calendar3"></i>
        {{ now()->format('Y/m/d') }}
    </div>
</div>

<!-- STATS GRID -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-bag-fill"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">إجمالي الطلبات</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-bell-fill"></i></div>
        <div><div class="stat-value">{{ $stats['new'] }}</div><div class="stat-label">طلبات جديدة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-check-circle-fill"></i></div>
        <div><div class="stat-value">{{ $stats['confirmed'] }}</div><div class="stat-label">مؤكدة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="bi bi-box-seam-fill"></i></div>
        <div><div class="stat-value">{{ $stats['preparing'] }}</div><div class="stat-label">قيد التجهيز</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon indigo"><i class="bi bi-truck"></i></div>
        <div><div class="stat-value">{{ $stats['shipped'] }}</div><div class="stat-label">تم الشحن</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-house-check-fill"></i></div>
        <div><div class="stat-value">{{ $stats['delivered'] }}</div><div class="stat-label">مسلمة</div></div>
    </div>
    @if(auth()->user()?->role === 'admin')
    <div class="stat-card">
        <div class="stat-icon gold"><i class="bi bi-cash-stack"></i></div>
        <div><div class="stat-value" style="font-size:1.2rem">{{ number_format($stats['revenue']) }}</div><div class="stat-label">الإيرادات (ج.م)</div></div>
    </div>
    @endif
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-calendar-day"></i></div>
        <div><div class="stat-value">{{ $stats['today'] }}</div><div class="stat-label">طلبات اليوم</div></div>
    </div>
</div>

<!-- FILTERS -->
<form action="{{ route('admin.orders.dashboard') }}" method="GET" class="filters-bar">
    <div class="filter-group" style="max-width:260px">
        <label class="filter-label">البحث</label>
        <div class="filter-search-wrap">

            <input type="text" name="search" placeholder="اسم / هاتف / رقم الطلب" value="{{ request('search') }}" onchange="this.form.submit()">
        </div>
    </div>
    <div class="filter-group" style="max-width:160px">
        <label class="filter-label">الحالة</label>
        <div class="custom-select-wrap">
            <select name="status" onchange="this.form.submit()">
                <option value="">كل الحالات</option>
                @foreach(['new' => 'جديد', 'confirmed' => 'مؤكد', 'preparing' => 'قيد التجهيز', 'shipped' => 'تم الشحن', 'delivered' => 'مسلم', 'cancelled' => 'ملغي', 'returned' => 'مرتجع'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down select-arrow"></i>
        </div>
    </div>
    <div class="filter-group" style="max-width:160px">
        <label class="filter-label">المحافظة</label>
        <div class="custom-select-wrap">
            <select name="governorate" onchange="this.form.submit()">
                <option value="">كل المحافظات</option>
                @foreach($governorates as $gov)
                    <option value="{{ $gov }}" {{ request('governorate') === $gov ? 'selected' : '' }}>{{ $gov }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down select-arrow"></i>
        </div>
    </div>
    <div class="filter-group" style="max-width:140px">
        <label class="filter-label">الشهر</label>
        <div class="custom-select-wrap">
            <select name="month" onchange="this.form.submit()">
                <option value="">كل الشهور</option>
                @foreach(['1'=>'يناير','2'=>'فبراير','3'=>'مارس','4'=>'أبريل','5'=>'مايو','6'=>'يونيو','7'=>'يوليو','8'=>'أغسطس','9'=>'سبتمبر','10'=>'أكتوبر','11'=>'نوفمبر','12'=>'ديسمبر'] as $num => $name)
                    <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down select-arrow"></i>
        </div>
    </div>
    <div class="filter-group" style="max-width:110px">
        <label class="filter-label">السنة</label>
        <div class="custom-select-wrap">
            <select name="year" onchange="this.form.submit()">
                <option value="">كل السنوات</option>
                @foreach(range(date('Y'), 2024) as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down select-arrow"></i>
        </div>
    </div>

    @if(request()->hasAny(['search', 'status', 'governorate', 'month', 'year']))
        <a href="{{ route('admin.orders.dashboard') }}" class="btn-reset"><i class="bi bi-x-lg"></i> مسح</a>
    @endif
</form>

<!-- VIEW SWITCHER -->
<div class="view-switcher">
    <button class="view-btn active" id="btnTable" onclick="switchView('table')">
        <i class="bi bi-list-ul"></i> جدول
    </button>
    <button class="view-btn" id="btnKanban" onclick="switchView('kanban')">
        <i class="bi bi-kanban-fill"></i> كانبان
    </button>
</div>

<!-- ===== TABLE VIEW ===== -->
<div id="tableView">

@php
    $arabicMonths = ['1'=>'يناير','2'=>'فبراير','3'=>'مارس','4'=>'أبريل','5'=>'مايو','6'=>'يونيو','7'=>'يوليو','8'=>'أغسطس','9'=>'سبتمبر','10'=>'أكتوبر','11'=>'نوفمبر','12'=>'ديسمبر'];
    $arabicDays   = ['Sunday'=>'الأحد','Monday'=>'الاثنين','Tuesday'=>'الثلاثاء','Wednesday'=>'الأربعاء','Thursday'=>'الخميس','Friday'=>'الجمعة','Saturday'=>'السبت'];
    
    $renderStats = function($orders) {
        $statsCfg = [
            'new'       => ['label' => 'جديد',   'icon' => 'bi-bell'],
            'confirmed' => ['label' => 'مؤكد',   'icon' => 'bi-check-circle'],
            'preparing' => ['label' => 'تجهيز',  'icon' => 'bi-box-seam'],
            'shipped'   => ['label' => 'شحن',    'icon' => 'bi-truck'],
            'delivered' => ['label' => 'مسلم',   'icon' => 'bi-house-check'],
            'cancelled' => ['label' => 'ملغي',   'icon' => 'bi-x-circle'],
            'returned'  => ['label' => 'مرتجع',  'icon' => 'bi-arrow-return-left'],
        ];
        $counts = $orders->groupBy('status')->map->count();
        $html = '<div class="group-stats">';
        foreach($statsCfg as $st => $cfg) {
            if($count = $counts->get($st)) {
                $html .= "<span class='stat-pill'><i class='bi {$cfg['icon']}'></i> {$cfg['label']} {$count}</span>";
            }
        }
        $html .= '</div>';
        return $html;
    };
@endphp

@if($allOrders->isNotEmpty())
<div class="orders-card" style="margin-bottom:20px">
    <div class="table-responsive">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>المحافظة</th>
                    <th>المنتجات</th>
                    <th>الإجمالي</th>
                    <th>الحالة</th>
                    <th>الوقت</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $lastMonth = null;
                    $lastDay = null;
                @endphp

                @foreach($allOrders as $order)
                    @php
                        $orderMonth = $order->created_at->format('Y-m');
                        $orderDay = $order->created_at->toDateString();
                    @endphp

                    @if($orderMonth !== $lastMonth)
                        @php
                            $lastMonth = $orderMonth;
                            $lastDay = null;
                            $monthCarbon = \Carbon\Carbon::parse($orderMonth . '-01');
                            $monthName = $arabicMonths[$monthCarbon->format('n')] . ' ' . $monthCarbon->format('Y');
                            $monthCount = $allOrders->filter(fn($o) => $o->created_at->format('Y-m') === $orderMonth)->count();
                        @endphp
                        <tr class="table-group-month-row" data-month="{{ $orderMonth }}" onclick="toggleTableMonth('{{ $orderMonth }}')">
                            <td colspan="8" class="table-group-month-cell">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-calendar-month"></i> {{ $monthName }}</span>
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <span class="badge bg-white text-purple-700 px-3 py-1 rounded-pill" style="color: var(--purple-700); font-weight: 700; font-size: 0.8rem;">
                                            {{ $monthCount }} طلب
                                        </span>
                                        <i class="bi bi-chevron-down group-chevron" style="font-size:.85rem;transition:transform .25s"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif

                    @if($orderDay !== $lastDay)
                        @php
                            $lastDay = $orderDay;
                            $dayCarbon = \Carbon\Carbon::parse($orderDay);
                            $dayName = $arabicDays[$dayCarbon->format('l')] . ' ' . $dayCarbon->format('d/m/Y');
                            $dayCount = $allOrders->filter(fn($o) => $o->created_at->toDateString() === $orderDay)->count();
                        @endphp
                        <tr class="table-group-day-row" data-month="{{ $orderMonth }}" data-day="{{ $orderDay }}" onclick="toggleTableDay('{{ $orderDay }}')">
                            <td colspan="8" class="table-group-day-cell">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-calendar3"></i> {{ $dayName }}</span>
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <span class="badge bg-purple-100 text-purple-700 px-3 py-1 rounded-pill" style="background: var(--purple-100); color: var(--purple-700); font-weight: 700; font-size: 0.75rem;">
                                            {{ $dayCount }} طلب
                                        </span>
                                        <i class="bi bi-chevron-down group-chevron" style="font-size:.8rem;transition:transform .25s"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif

                    <tr onclick="openDrawer({{ $order->id }})" data-id="{{ $order->id }}" data-month="{{ $orderMonth }}" data-day="{{ $orderDay }}">
                        <td><div class="order-number">{{ $order->order_number }}</div></td>
                        <td>
                            <div class="customer-name">{{ $order->customer_name }}</div>
                            <div class="customer-phone">{{ $order->customer_phone }}</div>
                        </td>
                        <td>{{ $order->governorate }}</td>
                        <td>
                            <span style="background:var(--purple-50);color:var(--purple-700);border-radius:8px;padding:4px 12px;font-size:.8rem;font-weight:700">
                                {{ $order->items->count() }} منتج
                            </span>
                        </td>
                        <td>
                            <div class="total-cell">{{ number_format($order->total_amount) }} <span class="currency">ج.م</span></div>
                        </td>
                        <td>
                            <span class="status-badge badge-{{ $order->status }}">
                                <i class="bi {{ $order->status_icon }}"></i> {{ $order->status_label }}
                            </span>
                        </td>
                        <td>
                            <div class="date-cell">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td onclick="event.stopPropagation()">
                            <div class="actions-cell">
                                @if($order->next_status)
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="display:inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $order->next_status }}">
                                    <button type="submit" class="action-btn advance-btn" title="{{ $order->next_status_label }}">
                                        <i class="bi bi-arrow-right-circle-fill"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="orders-card">
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-bag-x"></i></div>
        <div class="empty-title">لا توجد طلبات</div>
        <p>لم يتم العثور على أي طلبات بالفلاتر المحددة.</p>
    </div>
</div>
@endif

</div>{{-- end #tableView --}}

<!-- ===== KANBAN VIEW ===== -->
<div id="kanbanView" style="display:none">
@php
    $allCols = [
        'new'       => ['label'=>'جديد',        'icon'=>'bi-bell-fill',         'color'=>'#2563eb', 'bg'=>'#eff6ff'],
        'confirmed' => ['label'=>'مؤكد',        'icon'=>'bi-check-circle-fill', 'color'=>'#7c4dcc', 'bg'=>'#f3eeff'],
        'preparing' => ['label'=>'قيد التجهيز', 'icon'=>'bi-box-seam-fill',     'color'=>'#ea580c', 'bg'=>'#fff7ed'],
        'shipped'   => ['label'=>'تم الشحن',    'icon'=>'bi-truck',              'color'=>'#4338ca', 'bg'=>'#eef2ff'],
        'delivered' => ['label'=>'مسلّم',        'icon'=>'bi-house-check-fill',  'color'=>'#16a34a', 'bg'=>'#f0fdf4'],
        'cancelled' => ['label'=>'ملغي',         'icon'=>'bi-x-circle-fill',     'color'=>'#b91c1c', 'bg'=>'#fef2f2'],
        'returned'  => ['label'=>'مرتجع',        'icon'=>'bi-arrow-return-left', 'color'=>'#6b7280', 'bg'=>'#f9fafb'],
    ];
    $chunks = array_chunk($allCols, 3, true);
@endphp

@foreach($chunks as $chunkIndex => $cols)
<div class="kanban-board kanban-row-3" style="{{ $chunkIndex > 0 ? 'margin-top:14px' : '' }}">
    @foreach($cols as $status => $col)
    @php 
        $colOrders = $allOrders->where('status', $status)->sortByDesc('created_at');
        $monthGroups = $colOrders->groupBy(fn($o) => $o->created_at->format('Y-m'));
    @endphp
    <div class="kanban-col">
        <div class="kanban-col-header">
            <div class="kanban-col-title">
                <i class="bi {{ $col['icon'] }}" style="color:{{ $col['color'] }};filter:brightness(1.8)"></i>
                {{ $col['label'] }}
            </div>
            <span class="kanban-col-count" style="background:{{ $col['bg'] }};color:{{ $col['color'] }}">
                {{ $colOrders->count() }}
            </span>
        </div>
        <div class="kanban-col-body">
            @forelse($monthGroups as $monthKey => $mOrders)
                @php
                    $mDate = \Carbon\Carbon::parse($monthKey . '-01');
                    $mId = "kb-{$status}-" . str_replace('-', '', $monthKey);
                    $dayGroups = $mOrders->groupBy(fn($o) => $o->created_at->toDateString());
                @endphp
                
                <div class="kb-month-header" onclick="toggleGroup('{{ $mId }}')">
                    <span><i class="bi bi-calendar-month"></i> {{ $arabicMonths[$mDate->format('n')] }} {{ $mDate->format('Y') }}</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span style="font-size:0.65rem;font-weight:700;background:var(--purple-100);padding:1px 6px;border-radius:10px">{{ $mOrders->count() }}</span>
                        <i class="bi bi-chevron-up" id="chevron-{{ $mId }}" style="transition:transform .2s"></i>
                    </div>
                </div>

                <div id="group-{{ $mId }}">
                    @foreach($dayGroups as $dayKey => $dOrders)
                        @php
                            $dDate = \Carbon\Carbon::parse($dayKey);
                            $dId = "kb-{$status}-" . str_replace('-', '', $dayKey);
                        @endphp
                        <div class="kb-day-header" onclick="toggleGroup('{{ $dId }}')">
                            <span>{{ $arabicDays[$dDate->format('l')] }} {{ $dDate->format('d/m') }}</span>
                            <div style="display:flex;align-items:center;gap:8px">
                                <span style="font-size:0.65rem">{{ $dOrders->count() }}</span>
                                <i class="bi bi-chevron-up" id="chevron-{{ $dId }}" style="transition:transform .2s"></i>
                            </div>
                        </div>

                        <div id="group-{{ $dId }}" class="kb-day-body">
                            @foreach($dOrders as $order)
                            <div class="kanban-card" onclick="openDrawer({{ $order->id }})" data-id="{{ $order->id }}">
                                <div class="kanban-card-num">{{ $order->order_number }}</div>
                                <div class="kanban-card-name">{{ $order->customer_name }}</div>
                                <div class="kanban-card-phone">{{ $order->customer_phone }}</div>
                                <div class="kanban-card-footer">
                                    <span class="kanban-card-total">{{ number_format($order->total_amount) }} ج.م</span>
                                    <span class="kanban-card-time">{{ $order->created_at->format('d/m H:i') }}</span>
                                </div>
                                @if($order->next_status)
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" onclick="event.stopPropagation()">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $order->next_status }}">
                                    <button type="submit" class="kanban-advance-btn">
                                        <i class="bi bi-arrow-right-circle-fill"></i> {{ $order->next_status_label }}
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="kanban-empty"><i class="bi bi-inbox"></i></div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>
@endforeach
</div>

<!-- ORDER DRAWER -->
<div class="order-drawer-overlay" id="drawerOverlay" onclick="closeDrawer(event)">
    <div class="order-drawer" id="orderDrawer">
        <div class="drawer-header">
            <h3><i class="bi bi-bag-fill"></i> <span id="drawerTitle">تفاصيل الطلب</span></h3>
            <button class="drawer-close" onclick="closeDrawer()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="drawer-body" id="drawerBody">
            <div class="drawer-loading">
                <div class="spinner"></div>
                <span>جاري التحميل...</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
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
@endpush
