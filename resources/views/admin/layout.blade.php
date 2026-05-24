@extends('layouts.app')

@push('head')
<style>
/* Minimal Admin Layout — clean, spacious, less blocky */
.admin-layout { background: #f2f5f3; }
.admin-wrap {
    display: flex;
    min-height: 100vh;
}

/* Sidebar — narrow, clean, light gray instead of dark */
.admin-sidebar {
    width: 220px;
    background: #ecf4ef;
    border-right: 1px solid #c9dbcf;
    padding: 2rem 0 0;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    z-index: 100;
    display: flex;
    flex-direction: column;
}
.admin-sidebar-brand {
    font-size: 1.25rem;
    font-weight: 600;
    padding: 0 1.5rem 1.5rem;
    display: block;
    color: #0f2f1d;
    letter-spacing: -0.01em;
}
.admin-sidebar-brand span { color: var(--fs-primary); }

.admin-nav-section {
    margin-bottom: 1.5rem;
}
.admin-nav-label {
    font-size: .65rem;
    text-transform: uppercase;
    letter-spacing: .18em;
    color: #5f7267;
    padding: 0 1.5rem .5rem;
    font-weight: 500;
}
.admin-nav-item {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .65rem 1.5rem;
    color: #2f3b34;
    font-size: .88rem;
    text-decoration: none;
    transition: all .15s;
    border-left: 2px solid transparent;
}
.admin-nav-item:hover {
    color: #102c1d;
    background: #eef6f0;
}
.admin-nav-item.active {
    color: var(--fs-primary);
    background: #eaf7ee;
    border-left-color: var(--fs-primary);
}
.admin-nav-item i {
    font-size: 1rem;
    opacity: .7;
}

.admin-sidebar-bottom {
    margin-top: auto;
    margin-bottom: 0;
    border-top: 1px solid #d4e2d8;
    padding: .9rem 0;
    position: sticky;
    bottom: 0;
    background: #ecf4ef;
}

/* Main content area — spacious, clean */
.admin-main {
    flex: 1;
    margin-left: 220px;
    padding: 2rem 2.5rem;
    max-width: calc(100% - 220px);
    color: #1b2d23;
}
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #d9e3dd;
}
.admin-header h1 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}
.admin-user {
    display: flex;
    align-items: center;
    gap: .75rem;
    font-size: .85rem;
    color: #2f3b34;
}

/* Stats cards — minimal, no heavy shadows */
.stat-card {
    background: #fff;
    border: 1px solid #d9e3dd;
    padding: 1.5rem;
    text-align: center;
}
.stat-value {
    font-size: 2rem;
    font-weight: 300;
    color: #111;
    line-height: 1;
    margin-bottom: .25rem;
}
.stat-label {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: #5f7267;
}
.stat-card.primary { border-top: 2px solid var(--fs-primary); }

.status-pill {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 10px;
    border: 1px solid #d9e4dd;
    padding: .7rem .9rem;
    font-size: .82rem;
    font-weight: 600;
}
.status-pill strong { font-size: .96rem; }
.status-pill.pending { background: #fff8e8; border-color: #f3dc9f; color: #7d5a00; }
.status-pill.processing { background: #eef6ff; border-color: #bfd7fb; color: #1d4f94; }
.status-pill.completed { background: #ebfaef; border-color: #bde5c7; color: #1e6935; }
.status-pill.cancelled { background: #fff1f1; border-color: #f0c4c4; color: #8f2e2e; }

.admin-dashboard-wrap {
    max-width: 1180px;
    margin: 0 auto;
}
.admin-status-summary {
    padding: 1.15rem 1.2rem;
}
.admin-status-summary-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .7rem;
    margin-bottom: .9rem;
}
.admin-status-summary-head h5 {
    margin: 0;
    font-size: 1.02rem;
    font-weight: 600;
    color: #1a3024;
}
.admin-status-summary-head .meta {
    font-size: .66rem;
    text-transform: uppercase;
    letter-spacing: .13em;
    color: #63776b;
    font-weight: 600;
}
.admin-section {
    margin-top: 1.65rem;
}
.admin-section-title {
    margin: 0 0 .7rem;
    font-size: .95rem;
    font-weight: 600;
    color: #1e3227;
}
.dashboard-table .admin-table thead th {
    font-size: .72rem;
    letter-spacing: .08em;
}
.dashboard-empty {
    color: #6e7f75;
    font-size: .9rem;
}

.admin-form-shell {
    max-width: 1100px;
}
.admin-form-card {
    border: 1px solid #d9e3dd;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 8px 20px rgba(18, 43, 29, .06);
    overflow: hidden;
}
.admin-form-card .card-body {
    padding: 1.15rem;
}
.admin-form-card .card-footer {
    background: #f8fcf9;
    border-top: 1px solid #d9e3dd;
    padding: .95rem 1.15rem;
}
.admin-form-card .form-label.small {
    color: #415449;
    font-weight: 600;
}

.admin-status-card .form-select {
    border-radius: 10px;
    border-color: #cddacf;
    font-size: .92rem;
    font-weight: 500;
}
.admin-status-card .form-select:focus {
    border-color: #53b970;
    box-shadow: 0 0 0 .2rem rgba(43, 163, 76, .13);
}
.status-helper {
    border: 1px dashed #c9dbcf;
    background: #f7fbf8;
    border-radius: 10px;
    padding: .6rem .7rem;
    font-size: .8rem;
    color: #4f6157;
}

/* Tables — clean, spacious */
.admin-table-wrap {
    background: #fff;
    border: 1px solid #d9e3dd;
}
.admin-table {
    width: 100%;
    font-size: .88rem;
}
.admin-table thead th {
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #5f7267;
    font-weight: 500;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #d9e3dd;
    background: #f6faf7;
}
.admin-table tbody td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #edf3ef;
    color: #1e2e25;
}
.admin-table tbody tr:last-child td { border-bottom: 0; }
.admin-table tbody tr:hover { background: #f4faf6; }

/* Mobile */
@media (max-width: 991px) {
    .admin-sidebar { width: 100%; position: relative; height: auto; border-right: 0; border-bottom: 1px solid #ececee; padding: 1rem 0; }
    .admin-main { margin-left: 0; max-width: 100%; padding: 1.5rem; }
    .admin-form-card .card-body,
    .admin-form-card .card-footer { padding: .9rem; }
    .admin-status-summary { padding: .95rem; }
    .admin-status-summary-head { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush

@section('content')
<div class="admin-wrap">
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-brand">
            Papildai<span>Online</span>
        </a>

        <div class="admin-nav-section">
            <div class="admin-nav-label">Valdymas</div>
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-label">Parduotuvė</div>
            <a href="{{ route('admin.products.index') }}" class="admin-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box"></i> Produktai
            </a>
            <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tag"></i> Kategorijos
            </a>
            <a href="{{ route('admin.orders.index') }}" class="admin-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-bag"></i> Užsakymai
            </a>
            <a href="{{ route('admin.promo-codes.index') }}" class="admin-nav-item {{ request()->routeIs('admin.promo-codes.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i> Promo kodai
            </a>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-label">Turinys</div>
            <a href="{{ route('admin.nutrition-plans.index') }}" class="admin-nav-item {{ request()->routeIs('admin.nutrition-plans.*') ? 'active' : '' }}">
                <i class="bi bi-egg"></i> Mitybos planai
            </a>
            <a href="{{ route('admin.sport-plans.index') }}" class="admin-nav-item {{ request()->routeIs('admin.sport-plans.*') ? 'active' : '' }}">
                <i class="bi bi-heart-pulse"></i> Sporto planai
            </a>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-label">Pagalba</div>
            <a href="{{ route('admin.support.index') }}" class="admin-nav-item {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                <i class="bi bi-chat-left"></i> Žinutės
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="admin-nav-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i> Atsiliepimai
            </a>
        </div>

        <div class="admin-nav-section admin-sidebar-bottom">
            <a href="{{ route('home') }}" class="admin-nav-item">
                <i class="bi bi-arrow-up-right"></i> Į svetainę
            </a>
        </div>

    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <h1>@yield('title', 'Dashboard')</h1>
            <div class="admin-user">
                <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Atsijungti</button>
                </form>
            </div>
        </div>

        @yield('admin')
    </main>
</div>
@endsection
