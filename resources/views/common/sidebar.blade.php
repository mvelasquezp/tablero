<nav id="sidebar">
    <div id="dismiss">
        <i class="fas fa-arrow-left"></i>
    </div>

    <div class="sidebar-header">
        <h3>Bienvenido, {{ $usuario->des_alias }}</h3>
    </div>

    <ul class="list-unstyled components">
        @foreach($menu as $item)
        <li>
            @if(count($item->items) > 0)
            <a href="#item-{{ $item->id }}" data-toggle="collapse" aria-expanded="false"><i class="fas fa-caret-down"></i> {{ $item->nombre }}</a>
            <ul class="collapse list-unstyled" id="item-{{ $item->id }}">
                @foreach($item->items as $subitem)
                <li>
                    <a href="{{ url('intranet', [$item->url, $subitem->url]) }}">{{ $subitem->nombre }}</a>
                </li>
                @endforeach
            </ul>
            @else
            <li>
                <a href="{{ url('intranet', [$item->url]) }}">{{ $item->nombre }}</a>
            </li>
            @endif
        </li>
        @endforeach
    </ul>

    <ul class="list-unstyled CTAs">
        <li>
            <a href="https://bootstrapious.com/tutorial/files/sidebar.zip" class="download"><i class="fas fa-cogs"></i> Opciones</a>
        </li>
        <li>
            <a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </li>
    </ul>
</nav>