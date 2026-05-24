@extends('admin.layout')
@section('title', 'Naujienos')

@section('admin')
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Pridėti</a>
    </div>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead class="table-light"><tr><th></th><th>Pavadinimas</th><th>Publikuota</th><th>Statusas</th><th></th></tr></thead>
                <tbody>
                @forelse($news as $n)
                    <tr>
                        <td>@if($n->image)<img src="{{ $n->image }}" style="width:60px;height:40px;object-fit:cover;border-radius:4px">@endif</td>
                        <td><strong>{{ $n->title }}</strong><div class="small text-muted">{{ Str::limit($n->excerpt, 80) }}</div></td>
                        <td>{{ optional($n->published_at)->format('Y-m-d') }}</td>
                        <td>@if($n->is_published)<span class="badge bg-success">Publikuota</span>@else<span class="badge bg-secondary">Juodraštis</span>@endif</td>
                        <td class="text-end">
                            <a href="{{ route('admin.news.edit', $n) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.news.destroy', $n) }}" class="d-inline" onsubmit="return confirm('Ištrinti?')">
                                @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty<tr><td colspan="5" class="text-center text-muted py-4">Naujienų nėra.</td></tr>@endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $news->links() }}</div>
@endsection
