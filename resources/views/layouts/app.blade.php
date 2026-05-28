<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PapildaiOnline</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/app.css?v=13">
    @stack('head')
</head>
<body class="bg-light d-flex flex-column min-vh-100 @if(request()->routeIs('admin.*')) admin-layout @endif">
@if(!request()->routeIs('admin.*'))
        @include('partials.navbar')
    @endif

    <main class="flex-grow-1">
        @yield('content')
    </main>
@if(!request()->routeIs('admin.*'))
        @include('partials.footer')
    @endif
    @if(!request()->routeIs('admin.*'))
        <div class="accessibility-panel">
            <button class="accessibility-toggle" onclick="toggleAccessibility()" aria-label="Prieinamumo nustatymai" aria-expanded="false" aria-controls="accessibilityMenu" id="accessibilityToggle" type="button">
                <i class="bi bi-universal-access"></i>
            </button>
            <div class="accessibility-menu" id="accessibilityMenu" role="dialog" aria-label="Prieinamumo nustatymai">
                <h4><i class="bi bi-universal-access"></i> Prieinamumo nustatymai</h4>
                <div class="a11y-option">
                    <label>Didelis tekstas</label>
                    <div class="d-flex align-items-center gap-2">
                        <button class="a11y-btn" onclick="toggleLargeText()" id="largeTextBtn" type="button" aria-pressed="false">Įjungti</button>
                        <button class="a11y-reset" onclick="resetLargeText()" type="button" aria-label="Atstatyti didelį tekstą">↺</button>
                    </div>
                </div>
                <div class="a11y-option">
                    <label>Didelis kontrastas</label>
                    <div class="d-flex align-items-center gap-2">
                        <button class="a11y-btn" onclick="toggleHighContrast()" id="highContrastBtn" type="button" aria-pressed="false">Įjungti</button>
                        <button class="a11y-reset" onclick="resetHighContrast()" type="button" aria-label="Atstatyti kontrastą">↺</button>
                    </div>
                </div>
                <div class="a11y-option">
                    <label>Šrifto dydis</label>
                    <div class="d-flex align-items-center gap-2">
                        <button class="a11y-btn" onclick="changeFontSize(-1)" type="button" aria-label="Mažinti šriftą">A-</button>
                        <button class="a11y-btn" onclick="changeFontSize(1)" type="button" aria-label="Didinti šriftą">A+</button>
                        <button class="a11y-reset" onclick="resetFontSize()" type="button" aria-label="Atstatyti šrifto dydį">↺</button>
                    </div>
                </div>
                <div class="a11y-option">
                    <label>Mažiau animacijų</label>
                    <div class="d-flex align-items-center gap-2">
                        <button class="a11y-btn" onclick="toggleReducedMotion()" id="reducedMotionBtn" type="button" aria-pressed="false">Įjungti</button>
                        <button class="a11y-reset" onclick="resetReducedMotion()" type="button" aria-label="Atstatyti animacijas">↺</button>
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
        const A11Y_STORAGE_KEY = 'fitshop.a11y.v1';
        const MIN_FONT_SIZE = 14;
        const MAX_FONT_SIZE = 22;

        function setToggleState(buttonId, enabled) {
            const btn = document.getElementById(buttonId);
            if (!btn) return;
            btn.classList.toggle('active', enabled);
            btn.setAttribute('aria-pressed', enabled ? 'true' : 'false');
            btn.textContent = enabled ? 'Išjungti' : 'Įjungti';
        }

        function toggleAccessibility() {
            const menu = document.getElementById('accessibilityMenu');
            const toggle = document.getElementById('accessibilityToggle');
            const isOpen = menu.classList.toggle('active');
            if (toggle) toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }

        function closeAccessibility() {
            const menu = document.getElementById('accessibilityMenu');
            const toggle = document.getElementById('accessibilityToggle');
            if (menu) menu.classList.remove('active');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
        }

        function toggleLargeText() {
            document.body.classList.toggle('large-text');
            setToggleState('largeTextBtn', document.body.classList.contains('large-text'));
            persistA11y();
        }

        function toggleHighContrast() {
            document.body.classList.toggle('high-contrast');
            setToggleState('highContrastBtn', document.body.classList.contains('high-contrast'));
            persistA11y();
        }

        function toggleReducedMotion() {
            document.body.classList.toggle('reduced-motion');
            setToggleState('reducedMotionBtn', document.body.classList.contains('reduced-motion'));
            persistA11y();
        }

        function changeFontSize(delta) {
            const html = document.documentElement;
            const current = parseFloat(getComputedStyle(html).fontSize) || 16;
            const next = Math.min(MAX_FONT_SIZE, Math.max(MIN_FONT_SIZE, current + delta));
            html.style.fontSize = next + 'px';
            persistA11y();
        }

        function resetLargeText() {
            document.body.classList.remove('large-text');
            setToggleState('largeTextBtn', false);
            persistA11y();
        }

        function resetHighContrast() {
            document.body.classList.remove('high-contrast');
            setToggleState('highContrastBtn', false);
            persistA11y();
        }

        function resetReducedMotion() {
            document.body.classList.remove('reduced-motion');
            setToggleState('reducedMotionBtn', false);
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
                    reducedMotion: document.body.classList.contains('reduced-motion'),
                    fontSize: document.documentElement.style.fontSize || ''
                }));
            } catch (e) {}
        }

        function loadA11y() {
            try {
                const saved = JSON.parse(localStorage.getItem(A11Y_STORAGE_KEY) || '{}');
                if (saved.largeText) document.body.classList.add('large-text');
                if (saved.highContrast) document.body.classList.add('high-contrast');
                if (saved.reducedMotion) document.body.classList.add('reduced-motion');
                if (saved.fontSize) document.documentElement.style.fontSize = saved.fontSize;
                setToggleState('largeTextBtn', document.body.classList.contains('large-text'));
                setToggleState('highContrastBtn', document.body.classList.contains('high-contrast'));
                setToggleState('reducedMotionBtn', document.body.classList.contains('reduced-motion'));
            } catch (e) {}
        }

        document.addEventListener('click', function(e) {
            const panel = document.querySelector('.accessibility-panel');
            if (panel && !panel.contains(e.target)) closeAccessibility();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeAccessibility();
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









