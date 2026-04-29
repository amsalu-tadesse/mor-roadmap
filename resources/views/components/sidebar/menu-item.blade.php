@props(['menu_items'])

@foreach ($menu_items as $menu_item)
    <ul class="nav nav-treeview">
        @can($menu_item['permission'])
            <li class="nav-item">
                <a href="{{ route($menu_item['url']) }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ $menu_item['title'] }}</p>
                </a>
            </li>
        @endcan

    </ul>
@endforeach
