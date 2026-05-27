@extends('layouts.app')
@section('title', 'Registruotis')

@section('content')
<section class="container my-5" style="max-width:480px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h1 class="fw-bold h3 mb-4">Registruotis</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Vardas</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">El. paštas</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Slaptažodis</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    <div class="form-text small">Mažiausiai 8 simboliai, bent 1 didžioji raidė ir 1 skaičius</div>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Pakartokite slaptažodį</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="terms" value="1" id="terms" class="form-check-input @error('terms') is-invalid @enderror" required>
                    <label for="terms" class="form-check-label small">
                        Sutinku su <a href="{{ route('terms') }}" target="_blank">naudojimosi sąlygomis ir privatumo politika</a>
                    </label>
                    @error('terms')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary w-100" id="registerBtn">
                    Registruotis
                </button>
            </form>
            <script>document.querySelector('#registerBtn').closest('form').addEventListener('submit',function(){var b=document.getElementById('registerBtn');b.disabled=true;b.innerHTML='<span class="spinner-border spinner-border-sm"></span> Registruojama...';});</script>
            <hr>
            <div class="text-center small">Jau turite paskyrą? <a href="{{ route('login') }}">Prisijungti</a></div>
        </div>
    </div>
</section>
@endsection
