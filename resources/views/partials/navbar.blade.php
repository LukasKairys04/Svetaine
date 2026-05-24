{{-- Top Bar --}}
<div class="top-bar d-none d-lg-block">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-telephone"></i> +370 600 00000 (demo) |
                <i class="bi bi-envelope"></i> info@fitshop.lt (demo) |
                <i class="bi bi-geo-alt"></i> Vilnius, Lietuva (demo)
            </div>
            <div>
                <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
                <a href="#" class="me-3"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg sticky-top medical-navbar">
    <div class="container">
        {{-- Brand — Medical Style --}}
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-heart-pulse-fill" style="color: var(--fs-primary);"></i>
            <span style="color: var(--fs-primary);">PapildaiOnline</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <i class="bi bi-list fs-3"></i>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            {{-- Centrinė meniu juosta — tik svarbiausi link'ai, be ikonų. --}}
            <ul class="navbar-nav mx-lg-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('home')) active @endif" href="{{ route('home') }}">Pradžia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('shop.*') || request()->routeIs('product.*')) active @endif" href="{{ route('shop.index') }}">Parduotuvė</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('nutrition.*')) active @endif" href="{{ route('nutrition.index') }}">Mityba</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('sports.*')) active @endif" href="{{ route('sports.index') }}">Sportas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('support.*')) active @endif" href="{{ route('support.index') }}">Pagalba</a>
                </li>
            </ul>

            {{-- Dešinėje — deep search ir ikonos. --}}
            <div class="d-flex align-items-center gap-3">
                <form class="d-none d-lg-flex deep-search" role="search" method="GET" action="{{ route('shop.index') }}" style="width:280px">
                    <input type="search" name="q" value="{{ request('q') }}" class="search-input" placeholder="Ieškoti produktų..." aria-label="Paieška">
                    <button class="search-btn" type="submit"><i class="bi bi-search"></i></button>
                </form>

                <div class="nav-icons">
                    {{-- Paskyra --}}
                    <div class="dropdown">
                        <button class="nav-icon-btn" data-bs-toggle="dropdown" aria-label="Paskyra">
                            <i class="bi bi-person"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @auth
                                <li><span class="dropdown-item-text text-muted">{{ auth()->user()->name }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('account.index') }}">Mano paskyra</a></li>
                                <li><a class="dropdown-item" href="{{ route('account.orders') }}">Užsakymai</a></li>
                                @if(auth()->user()->is_admin)
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Atsijungti</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('login') }}">Prisijungti</a></li>
                                <li><a class="dropdown-item" href="{{ route('register') }}">Registruotis</a></li>
                            @endauth
                        </ul>
                    </div>

                    {{-- Krepšelis --}}
                    <a class="nav-icon-btn" href="{{ route('cart.index') }}" aria-label="Krepšelis">
                        <i class="bi bi-bag"></i>
                        @php $cartCount = \App\Services\CartService::countStatic(); @endphp
                        @if($cartCount > 0)
                            <span class="nav-icon-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
