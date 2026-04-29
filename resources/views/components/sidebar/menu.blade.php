@php
$menus = config('menus');

@endphp

<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                   Dashboard
                </p>
            </a>
        </li>
        @foreach ($menus as $menu)

        @canany($menu['permissions'])
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas {{ $menu['menu_icon'] }}"></i>
                <p>
                    {{ $menu['name'] }}
                    <i class="fas fa-angle-left right"></i>
                    <span class="badge badge-info right" style="margin-right:-1%;">
                        {{ count($menu['menu_item']) }} </span>
                </p>
            </a>
            <x-sidebar.menu-item :menu_items="$menu['menu_item']" />
        </li>
        @endcanany

        @endforeach
    </ul>
</nav>
