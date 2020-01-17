@can("viewAny", \App\Review::class)
    @if ($needModerate)
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle{{ strstr($currentRoute, 'admin.reviews') !== FALSE ? ' active' : '' }}"
               href="#"
               id="user-dropdown"
               role="button"
               data-toggle="dropdown"
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
