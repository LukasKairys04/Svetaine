<div class="list-group shadow-sm account-menu">
    <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account.index') ? 'active' : '' }}"><i class="bi bi-person"></i> Profilis</a>
    <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account.orders*') || request()->routeIs('account.order') ? 'active' : '' }}"><i class="bi bi-bag-check"></i> Užsakymai</a>
    @if(auth()->user()?->is_admin)
        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2"></i> Admin panelė</a>
    @endif
    <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button class="list-group-item list-group-item-action text-danger" type="submit"><i class="bi bi-box-arrow-right"></i> Atsijungti</button>
    </form>
</div>
