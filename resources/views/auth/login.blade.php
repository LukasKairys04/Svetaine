@extends('layouts.app')
@section('title', 'Prisijungti')

@section('content')
<section class="container my-5" style="max-width:480px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h1 class="fw-bold h3 mb-4">Prisijungti</h1>

            <form method="POST" action="{{ route('login') }}">
                @if(request('next'))
                    <input type="hidden" name="next" value="{{ request('next') }}">
                @endif
                @csrf
                <div class="mb-3">
                    <label class="form-label">El. paštas</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Slaptažodis</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Prisiminti mane</label>
                </div>
                <button class="btn btn-primary w-100">Prisijungti</button>
            </form>
            <hr>
            <div class="text-center small">Neturite paskyros? <a href="{{ route('register') }}">Registruotis</a></div>
        </div>
    </div>
</section>
@endsection
