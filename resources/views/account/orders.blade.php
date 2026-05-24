@extends('layouts.app')
@section('title', 'Mano užsakymai')

@section('content')
<section class="container my-4">
    <h1 class="fw-bold mb-4"><i class="bi bi-bag-check"></i> Mano užsakymai</h1>
    <div class="row g-4">
        <div class="col-md-3">@include('account.partials.menu')</div>
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead class="table-light">
                            <tr><th>Nr.</th><th>Data</th><th>Suma</th><th>Būsena</th><th>Mokėjimas</th><th></th></tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $o)
                            <tr>
                                <td><strong>{{ $o->order_number }}</strong></td>
                                <td>{{ $o->created_at->format('Y-m-d H:i') }}</td>
                                <td>€{{ number_format($o->total, 2) }}</td>
                                <td><span class="badge bg-primary">{{ ucfirst($o->status) }}</span></td>
                                <td><span class="badge bg-{{ $o->payment_status === 'paid' ? 'success' : 'secondary' }}">{{ ucfirst($o->payment_status) }}</span></td>
                                <td><a href="{{ route('account.order', $o) }}" class="btn btn-sm btn-outline-primary">Peržiūrėti</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Dar nėra užsakymų.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">{{ $orders->links() }}</div>
        </div>
    </div>
</section>
@endsection
