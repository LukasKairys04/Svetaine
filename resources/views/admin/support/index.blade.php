@extends('admin.layout')
@section('title', 'Pagalbos žinutės')

@section('admin')
    <form method="GET" class="card p-3 mb-3 shadow-sm">
        <div class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Visos būsenos</option>
                    @foreach(['new','in_progress','resolved','closed'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead class="table-light"><tr><th>Data</th><th>Nuo</th><th>Tema</th><th>Būsena</th><th></th></tr></thead>
                <tbody>
                @forelse($messages as $m)
                    <tr>
                        <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
                        <td><strong>{{ $m->name }}</strong><div class="small text-muted">{{ $m->email }}</div></td>
                        <td>{{ Str::limit($m->subject, 80) }}</td>
                        <td>
                            @php $colors = ['new' => 'danger', 'in_progress' => 'warning text-dark', 'resolved' => 'success', 'closed' => 'secondary']; @endphp
                            <span class="badge bg-{{ $colors[$m->status] ?? 'secondary' }}">{{ ucfirst($m->status) }}</span>
                        </td>
                        <td class="text-end"><a href="{{ route('admin.support.show', $m->id) }}" class="btn btn-sm btn-outline-primary">Atidaryti</a></td>
                    </tr>
                @empty<tr><td colspan="5" class="text-center text-muted py-4">Žinučių nėra.</td></tr>@endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $messages->links() }}</div>
@endsection
