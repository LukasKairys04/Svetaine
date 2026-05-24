@extends('admin.layout')
@section('title', 'Užsakymai')

@section('admin')
    <form method="GET" class="card p-3 mb-3 shadow-sm">
        <div class="row g-2">
            <div class="col-md-6"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nr. / vardas / el. paštas"></div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Visos būsenos</option>
                    @foreach(['pending','paid','processing','shipped','completed','cancelled','refunded'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary w-100">Filtruoti</button></div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead class="table-light"><tr><th>Nr.</th><th>Data</th><th>Klientas</th><th>Suma</th><th>Būsena</th><th>Mokėjimas</th><th></th></tr></thead>
                <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td><strong>{{ $o->order_number }}</strong></td>
                        <td>{{ $o->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $o->billing_name }}<div class="small text-muted">{{ $o->billing_email }}</div></td>
                        <td>€{{ number_format($o->total, 2) }}</td>
                        <td><span class="badge bg-primary">{{ $o->status }}</span></td>
                        <td><span class="badge bg-{{ $o->payment_status === 'paid' ? 'success' : 'secondary' }}">{{ $o->payment_status }}</span></td>
                        <td class="text-end"><a href="{{ route('admin.orders.show', $o) }}" class="btn btn-sm btn-outline-primary">Atidaryti</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Užsakymų nerasta.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $orders->links() }}</div>
@endsection
