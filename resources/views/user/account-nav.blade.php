<ul class="account-nav">
    <li><a href="{{ route('user.index') }}" class="menu-link menu-link_us-s">Panel de Control</a></li>
    <li><a href="{{ route('user.orders') }}" class="menu-link menu-link_us-s">Ordenes</a></li>
    <li><a href="account-address.html" class="menu-link menu-link_us-s">Direccion</a></li>
    <li><a href="account-details.html" class="menu-link menu-link_us-s">Detalles de la cuenta</a></li>
    <li>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <a href="{{ route('logout') }}" class="menu-link menu-link_us-s" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
        </form>
    </li>
</ul>