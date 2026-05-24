<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PapildaiOnline</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Inter įkraunam su 300 svoriu didelių antraščių plonumui. --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=7">
    @stack('head')
</head>
<body class="bg-light d-flex flex-column min-vh-100 @if(request()->routeIs('admin.*')) admin-layout @endif">
    {{-- Admin puslapiuose nerodom standartinio navbar — ten yra savas sidebar. --}}
    @if(!request()->routeIs('admin.*'))
        @include('partials.navbar')
    @endif

    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- Admin puslapiuose nerodom footer ir floating cart. --}}
    @if(!request()->routeIs('admin.*'))
        @include('partials.footer')
    @endif

    @if(!request()->routeIs('admin.*'))
        {{-- Accessibility Settings Panel --}}
        <div class="accessibility-panel">
            <button class="accessibility-toggle" onclick="toggleAccessibility()" aria-label="Prieinamumo nustatymai">
                <i class="bi bi-universal-access"></i>
            </button>
            <div class="accessibility-menu" id="accessibilityMenu">
                <h4><i class="bi bi-universal-access"></i> Prieinamumo nustatymai</h4>
                <div class="a11y-option">
                    <label>Didelis tekstas</label>
                    <div class="d-flex align-items-center gap-2">
                        <button class="a11y-btn" onclick="toggleLargeText()" id="largeTextBtn">Įjungti</button>
                        <button class="a11y-reset" onclick="resetLargeText()" aria-label="Atstatyti didelį tekstą">↺</button>
                    </div>
                </div>
                <div class="a11y-option">
                    <label>Didelis kontrastas</label>
                    <div class="d-flex align-items-center gap-2">
                        <button class="a11y-btn" onclick="toggleHighContrast()" id="highContrastBtn">Įjungti</button>
                        <button class="a11y-reset" onclick="resetHighContrast()" aria-label="Atstatyti kontrastą">↺</button>
                    </div>
                </div>
                <div class="a11y-option">
                    <label>Šrifto dydis</label>
                    <div class="d-flex align-items-center gap-2">
                        <button class="a11y-btn" onclick="changeFontSize(-1)">A-</button>
                        <button class="a11y-btn" onclick="changeFontSize(1)">A+</button>
                        <button class="a11y-reset" onclick="resetFontSize()" aria-label="Atstatyti šrifto dydį">↺</button>
                    </div>
                </div>
            </div>
        </div>

    @endif

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1200;">
        @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0 app-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4500">
                <div class="d-flex">
                    <div class="toast-body">{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Uždaryti"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast align-items-center text-bg-danger border-0 app-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5500">
                <div class="d-flex">
                    <div class="toast-body">{{ session('error') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Uždaryti"></button>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Accessibility functions
        const A11Y_STORAGE_KEY = 'fitshop.a11y.v1';
        function toggleAccessibility() {
            document.getElementById('accessibilityMenu').classList.toggle('active');
        }
        function toggleLargeText() {
            document.body.classList.toggle('large-text');
            const btn = document.getElementById('largeTextBtn');
            btn.classList.toggle('active');
            btn.textContent = btn.classList.contains('active') ? 'Išjungti' : 'Įjungti';
            persistA11y();
        }
        function toggleHighContrast() {
            document.body.classList.toggle('high-contrast');
            const btn = document.getElementById('highContrastBtn');
            btn.classList.toggle('active');
            btn.textContent = btn.classList.contains('active') ? 'Išjungti' : 'Įjungti';
            persistA11y();
        }
        function changeFontSize(delta) {
            const html = document.documentElement;
            const current = parseFloat(getComputedStyle(html).fontSize);
            html.style.fontSize = (current + delta) + 'px';
            persistA11y();
        }
        function resetLargeText() {
            document.body.classList.remove('large-text');
            const btn = document.getElementById('largeTextBtn');
            btn.classList.remove('active');
            btn.textContent = 'Įjungti';
            persistA11y();
        }
        function resetHighContrast() {
            document.body.classList.remove('high-contrast');
            const btn = document.getElementById('highContrastBtn');
            btn.classList.remove('active');
            btn.textContent = 'Įjungti';
            persistA11y();
        }
        function resetFontSize() {
            document.documentElement.style.fontSize = '';
            persistA11y();
        }
        function persistA11y() {
            try {
                localStorage.setItem(A11Y_STORAGE_KEY, JSON.stringify({
                    largeText: document.body.classList.contains('large-text'),
                    highContrast: document.body.classList.contains('high-contrast'),
                    fontSize: document.documentElement.style.fontSize || ''
                }));
            } catch (e) {}
        }
        function loadA11y() {
            try {
                const saved = JSON.parse(localStorage.getItem(A11Y_STORAGE_KEY) || '{}');
                if (saved.largeText && !document.body.classList.contains('large-text')) toggleLargeText();
                if (saved.highContrast && !document.body.classList.contains('high-contrast')) toggleHighContrast();
                if (saved.fontSize) document.documentElement.style.fontSize = saved.fontSize;
            } catch (e) {}
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            const panel = document.querySelector('.accessibility-panel');
            if (panel && !panel.contains(e.target)) {
                document.getElementById('accessibilityMenu').classList.remove('active');
            }
        });

        document.querySelectorAll('.app-toast').forEach(el => {
            const t = bootstrap.Toast.getOrCreateInstance(el);
            t.show();
        });

        loadA11y();
    </script>
    @stack('scripts')
</body>
</html>
