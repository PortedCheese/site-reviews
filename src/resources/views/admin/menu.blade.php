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
        @role('admin')
        <a href="{{ route('admin.reviews.settings') }}"
           class="dropdown-item">
            Настройки
        </a>
        @endrole
        <a href="{{ route('admin.reviews.index') }}"
           class="dropdown-item">
            Список
        </a>
        @if ($needModerate)
            <a href="{{ route('admin.reviews.need-moderate') }}"
               class="dropdown-item">
                Ожидают модерации
            </a>
        @endif
    </div>
</li>