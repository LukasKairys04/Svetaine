@extends('admin.layout')
@section('title', 'Žinutė — ' . $message->subject)

@section('admin')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold m-0">{{ $message->subject }}</h1>
        <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Atgal</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <strong>{{ $message->name }}</strong> &lt;{{ $message->email }}&gt;
                        <div class="small text-muted">{{ $message->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="p-3 bg-light rounded mb-3" style="white-space:pre-wrap">{{ $message->message }}</div>

                    @if($message->admin_reply)
                        <div class="p-3 bg-primary-subtle rounded">
                            <strong>Admin atsakymas:</strong><br>
                            <div style="white-space:pre-wrap">{{ $message->admin_reply }}</div>
                            <div class="small text-muted mt-2">{{ optional($message->replied_at)->format('Y-m-d H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <form method="POST" action="{{ route('admin.support.update', $message->id) }}" class="card shadow-sm mb-3 admin-status-card">
                @csrf @method('PUT')
                <div class="card-body">
                    <h5>Atsakymas / būsena</h5>
                    <div class="mb-3">
                        <label class="form-label small">Būsena</label>
                        <select name="status" class="form-select">
                            @foreach([
                                'new' => 'Nauja',
                                'in_progress' => 'Vykdoma',
                                'resolved' => 'Išspręsta',
                                'closed' => 'Uždaryta'
                            ] as $s => $label)
                                <option value="{{ $s }}" @selected($message->status === $s)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="status-helper mb-3">Pasirink aiškią būseną ir, jei reikia, palik trumpą atsakymą klientui.</div>
                    <div class="mb-3">
                        <label class="form-label small">Atsakymas</label>
                        <textarea name="admin_reply" rows="5" class="form-control">{{ old('admin_reply', $message->admin_reply) }}</textarea>
                    </div>
                    <button class="btn btn-primary w-100">Išsaugoti</button>
                </div>
            </form>
            <form method="POST" action="{{ route('admin.support.destroy', $message->id) }}" onsubmit="return confirm('Ištrinti žinutę?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Ištrinti</button>
            </form>
        </div>
    </div>
@endsection
