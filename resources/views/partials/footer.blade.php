<footer class="footer-dark">
    <div class="container">
        <div class="footer-minimal">
            <h4><span style="color: var(--fs-primary)">PapildaiOnline</span></h4>
            <div class="footer-links">
                <a href="{{ route('shop.index') }}">Parduotuvė</a>
                <a href="{{ route('nutrition.index') }}">Mityba</a>
                <a href="{{ route('sports.index') }}">Sportas</a>
                <a href="{{ route('support.index') }}">Kontaktai</a>
            </div>
            <div class="small text-muted mt-2">
                © {{ date('Y') }} PapildaiOnline. Visos teisės saugomos.
            </div>
        </div>
    </div>
</footer>
