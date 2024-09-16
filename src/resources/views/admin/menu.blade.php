@can("viewAny", \App\Review::class)
    @if ($needModerate)
        @if ($theme == "sb-admin")
            @php($active = strstr($currentRoute, 'admin.reviews') !== FALSE)
            <li class="nav-item dropdown{{ $active ? ' active' : '' }}">
                <a class="nav-link"
                   href="#"
                   data-bs-toggle="collapse"
                   data-bs-target="#collapse-reviews-menu"
                   aria-controls="#collapse-reviews-menu"
                   aria-expanded="{{ $active ? "true" : "false" }}">
                    @isset($ico)
                        <i class="{{ $ico }}"></i>
                    @endisset
                    <span>Отзывы</span>
                </a>
                <div id="collapse-reviews-menu" class="collapse{{ $active ? " show" : "" }}" data-bs-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a href="{{ route('admin.reviews.index') }}"
                           class="collapse-item{{ $currentRoute == "admin.reviews.index" ? " active" : "" }}">
                            <span>Список</span>
                        </a>
                        <a href="{{ route('admin.reviews.need-moderate') }}"
                           class="collapse-item{{ $currentRoute == "admin.reviews.need-moderate" ? " active" : "" }}">
                            <span>Ожидают модерации</span>
                        </a>
                    </div>
                </div>
            </li>
        @else
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle{{ strstr($currentRoute, 'admin.reviews') !== FALSE ? ' active' : '' }}"
                   href="#"
                   id="user-dropdown"
                   role="button"
                   data-bs-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    @isset($ico)
                        <i class="{{ $ico }}"></i>
                    @endisset
                    Отзывы
                </a>
                <div class="dropdown-menu" aria-labelledby="user-dropdown">
                    <a href="{{ route('admin.reviews.index') }}"
                       class="dropdown-item">
                        Список
                    </a>
                    <a href="{{ route('admin.reviews.need-moderate') }}"
                       class="dropdown-item">
                        Ожидают модерации
                    </a>
                </div>
            </li>
        @endif
    @else
        <li class="nav-item">
            <a href="{{ route("admin.reviews.index") }}" class="nav-link">
                @isset($ico)
                    <i class="{{ $ico }}"></i>
                @endisset
                Отзывы
            </a>
        </li>
    @endif
@endcan
