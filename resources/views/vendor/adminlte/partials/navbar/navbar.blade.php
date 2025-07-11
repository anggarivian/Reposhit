<nav class="main-header navbar 
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }} 
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- Notifikasi Lonceng --}}
        <li class="nav-item dropdown" id="notif-dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge d-none" id="notif-count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notif-menu">
                <span class="dropdown-header" id="notif-header">0 Notifikasi</span>
                <div class="dropdown-divider"></div>
                <div id="notif-items"></div>
            </div>
        </li>

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>
</nav>


<script>
document.addEventListener('DOMContentLoaded', function () {
    fetchNotifications();

    function fetchNotifications() {
        fetch("{{ url('/notifikasi/fetch') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin' // important for session-based auth
        })
        .then(response => response.json())
        .then(data => {
            const count = data.count;
            const notifs = data.data;
            console.log(count);
            console.log(notifs);
            
            const countElem = document.getElementById('notif-count');
            const headerElem = document.getElementById('notif-header');
            const itemsContainer = document.getElementById('notif-items');

            if (count > 0) {
                countElem.classList.remove('d-none');
                countElem.textContent = count;
            } else {
                countElem.classList.add('d-none');
            }

            headerElem.textContent = count + ' Notifikasi';
            itemsContainer.innerHTML = '';

            notifs.forEach(notif => {
                const item = document.createElement('a');
                item.className = 'dropdown-item';
                item.href = '#';
                item.innerHTML = `
                    <i class="fas fa-exclamation-triangle mr-2"></i> ${notif.deskripsi}
                    <span class="float-right text-muted text-sm">${timeSince(new Date(notif.created_at))}</span>
                `;
                itemsContainer.appendChild(item);
            });
        })
        .catch(err => console.error('Notification fetch failed:', err));
    }

    function timeSince(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval + " jam lalu";
        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + " menit lalu";
        return "Baru saja";
    }

    // Auto refresh every 60 seconds
    setInterval(fetchNotifications, 60000);
});
</script>
