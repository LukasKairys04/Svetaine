@extends('admin.layout')
@section('title', 'Redaguoti atsiliepimą')

@section('admin')
    <div class="mb-3">
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Atgal</a>
    </div>

    <div class="card shadow-sm" style="max-width:640px">
        <div class="card-body p-4">
            <h5 class="mb-1">Atsiliepimas apie: <strong>{{ $review->product->name ?? '—' }}</strong></h5>
            <div class="text-muted small mb-4">Autorius: {{ $review->user->name ?? 'Nežinomas' }} &bull; {{ $review->created_at->format('Y-m-d H:i') }}</div>

            <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Įvertinimas</label>
                    <select name="rating" class="form-select" style="max-width:160px">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @selected(old('rating', $review->rating) == $i)>{{ $i }} ⭐</option>
                        @endfor
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Antraštė</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $review->title) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Komentaras</label>
                    <textarea name="comment" class="form-control" rows="5">{{ old('comment', $review->comment) }}</textarea>
                </div>

                <div class="mb-4 form-check">
                    <input type="hidden" name="approved" value="0">
                    <input type="checkbox" name="approved" value="1" id="approved" class="form-check-input" @checked(old('approved', $review->approved))>
                    <label for="approved" class="form-check-label">Patvirtintas</label>
                </div>

                <button class="btn btn-primary">Išsaugoti</button>
            </form>
        </div>
    </div>
@endsection
