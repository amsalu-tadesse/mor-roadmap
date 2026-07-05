@props(['menu_items'])

@foreach ($menu_items as $menu_item)
    <ul class="nav nav-treeview">
        @can($menu_item['permission'])
            <li class="nav-item">
                {{-- <a href="{{ route($menu_item['url']) }}" class="nav-link">
                  @if(request()->routeIs($menu_item['url']))
    <i class="fas fa-dot-circle submenu-icon active"></i>
@else
    <i class="far fa-circle submenu-icon"></i>
@endif
                    <p>{{ $menu_item['title'] }}</p>
                </a> --}}




                 <a href="{{ route($menu_item['url']) }}"
   class="nav-link {{ request()->routeIs($menu_item['url']) ? 'active' : '' }}">

    @if(request()->routeIs($menu_item['url']))
        <i class="fas fa-dot-circle submenu-icon text-primary"></i>
    @else
        <i class="far fa-circle submenu-icon"></i>
    @endif

    <p class="{{ request()->routeIs($menu_item['url']) ? 'text-primary fw-semibold' : '' }}">
        {{ $menu_item['title'] }}
    </p>

</a>



            </li>
        @endcan

    </ul>
@endforeach
