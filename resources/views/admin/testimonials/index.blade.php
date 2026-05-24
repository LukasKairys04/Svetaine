@extends('admin.layout')
@section('title', 'Atsiliepimai')

@section('admin')
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead class="table-light"><tr><th>Data</th><th>Vardas</th><th>Rating</th><th>Atsiliepimas</th><th>Statusas</th><th></th></tr></thead>
                <tbody>
                @forelse($testimonials as $t)
                    <tr>
                        <td>{{ $t->created_at->format('Y-m-d') }}</td>
                        <td><strong>{{ $t->name }}</strong></td>
                        <td>{{ number_format($t->rating, 1) }} ⭐</td>
                        <td>{{ Str::limit($t->content, 120) }}</td>
                        <td>@if($t->approved)<span class="badge bg-success">Patvirtintas</span>@else<span class="badge bg-warning text-dark">Laukia</span>@endif</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.testimonials.update', $t) }}" class="d-inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="approved" value="{{ $t->approved ? 0 : 1 }}">
                                <button class="btn btn-sm btn-outline-{{ $t->approved ? 'warning' : 'success' }}">
                                    <i class="bi bi-{{ $t->approved ? 'x-lg' : 'check2' }}"></i> {{ $t->approved ? 'Atšaukti' : 'Patvirtinti' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" class="d-inline" onsubmit="return confirm('Ištrinti?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty<tr><td colspan="6" class="text-center text-muted py-4">Atsiliepimų nėra.</td></tr>@endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $testimonials->links() }}</div>
@endsection
