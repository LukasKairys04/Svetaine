@extends('admin.layout')

@section('title', 'Sporto planai')



@section('admin')

    <div class="d-flex justify-content-end align-items-center mb-3">

        <a href="{{ route('admin.sport-plans.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Pridėti</a>

    </div>

    <div class="card shadow-sm">

        <div class="table-responsive">

            <table class="table align-middle m-0">

                <thead class="table-light"><tr><th>Pavadinimas</th><th>Sporto šaka</th><th>Lygis</th><th>Tikslas</th><th>Savaičių</th><th>Akt.</th><th></th></tr></thead>

                <tbody>

                @forelse($plans as $p)

                    <tr>

                        <td><strong>{{ $p->name }}</strong></td>

                        <td>{{ $p->sport?->name ?? '—' }}</td>

                        <td><span class="badge bg-danger">{{ $p->level }}</span></td>

                        <td><span class="badge bg-primary">{{ $p->goal }}</span></td>

                        <td>{{ $p->duration_weeks }}</td>

                        <td>@if($p->is_active)<i class="bi bi-check-circle-fill text-success"></i>@else<i class="bi bi-x-circle text-muted"></i>@endif</td>

                        <td class="text-end">

                            <a href="{{ route('sports.plan', $p->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>

                            <a href="{{ route('admin.sport-plans.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>

                            <form method="POST" action="{{ route('admin.sport-plans.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Ištrinti?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>

                        </td>

                    </tr>

                @empty<tr><td colspan="7" class="text-center text-muted py-4">Planų nėra.</td></tr>@endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="mt-3 d-flex justify-content-center">{{ $plans->links() }}</div>

@endsection

