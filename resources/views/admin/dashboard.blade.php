@extends('admin.layout')
@section('title', 'Dashboard')

@section('admin')
    <div class="admin-dashboard-wrap">
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['products'] ?? 0 }}</div>
                    <div class="stat-label">Produktai</div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['users'] ?? 0 }}</div>
                    <div class="stat-label">Vartotojai</div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['orders'] ?? 0 }}</div>
                    <div class="stat-label">Užsakymai</div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card primary">
                    <div class="stat-value">€{{ number_format($stats['revenue'] ?? 0, 0, ',', ' ') }}</div>
                    <div class="stat-label">Pajamos</div>
                </div>
            </div>
        </div>

        <div class="admin-section mt-2">
            <div class="stat-card admin-status-summary">
                <div class="admin-status-summary-head">
                    <h5>Užsakymų būsenų suvestinė</h5>
                    <span class="meta">Greita apžvalga</span>
                </div>
                <div class="row g-2">
                    <div class="col-6 col-md-3"><div class="status-pill pending">Nauji <strong>{{ $orderStatus['pending'] ?? 0 }}</strong></div></div>
                    <div class="col-6 col-md-3"><div class="status-pill processing">Vykdomi <strong>{{ $orderStatus['processing'] ?? 0 }}</strong></div></div>
                    <div class="col-6 col-md-3"><div class="status-pill completed">Užbaigti <strong>{{ $orderStatus['completed'] ?? 0 }}</strong></div></div>
                    <div class="col-6 col-md-3"><div class="status-pill cancelled">Atšaukti <strong>{{ $orderStatus['cancelled'] ?? 0 }}</strong></div></div>
                </div>
                <div class="small text-muted mt-3">
                    Laukiančios žinutės: <strong>{{ $stats['pending_support'] ?? 0 }}</strong> ·
                    Nepatvirtinti atsiliepimai: <strong>{{ $stats['pending_testimonials'] ?? 0 }}</strong>
                </div>
            </div>
        </div>

        <div class="admin-section">
            <h6 class="admin-section-title">Naujausi užsakymai</h6>
            <div class="admin-table-wrap dashboard-table">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Užsakymas</th>
                            <th>Klientas</th>
                            <th>Data</th>
                            <th class="text-end">Suma</th>
                            <th>Būsena</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $o)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $o) }}" style="color: var(--fs-primary); font-weight: 500;">
                                        {{ $o->order_number }}
                                    </a>
                                </td>
                                <td>{{ $o->billing_name }}</td>
                                <td style="color: #888; font-size: .85rem;">{{ $o->created_at->format('Y-m-d') }}</td>
                                <td class="text-end" style="font-weight: 500;">€{{ number_format($o->total, 2) }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $o->status === 'completed' ? '#22c55e' : ($o->status === 'pending' ? '#f59e0b' : '#6b7280') }}; font-size: .7rem; font-weight: 500; padding: .35rem .6rem; border-radius: 4px;">
                                        {{ $o->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 dashboard-empty">Nėra užsakymų</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-section">
            <h6 class="admin-section-title">Naujausi atsiliepimai</h6>
            <div class="admin-table-wrap dashboard-table">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Vardas</th>
                            <th>Reitingas</th>
                            <th>Atsiliepimas</th>
                            <th>Būsena</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTestimonials as $t)
                            <tr>
                                <td style="color: #888; font-size: .85rem;">{{ $t->created_at->format('Y-m-d') }}</td>
                                <td>{{ $t->name }}</td>
                                <td>{{ number_format($t->rating, 1) }} ★</td>
                                <td>{{ Str::limit($t->content, 90) }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $t->approved ? '#22c55e' : '#f59e0b' }}; font-size: .7rem; font-weight: 500; padding: .35rem .6rem; border-radius: 4px;">
                                        {{ $t->approved ? 'Patvirtintas' : 'Laukia' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 dashboard-empty">Nėra atsiliepimų</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
