<?php

namespace PortedCheese\SiteReviews\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use PortedCheese\BaseSettings\Traits\InitPolicy;

class ReviewPolicy
{
    use HandlesAuthorization;
    use InitPolicy {
        InitPolicy::__construct as private __ipoConstruct;
    }

    const VIEW_ALL = 2;
    const VIEW = 8;
    const UPDATE = 16;
    const DELETE = 32;
    const PUBLISH = 64;

    public function __construct()
    {
        $this->__ipoConstruct("ReviewPolicy");
    }

    /**
     * Получить права доступа.
     *
     * @return array
     */
    public static function getPermissions()
    {
        return [
            self::VIEW_ALL => "Просмотр всех",
            self::VIEW => "Просмотр",
            self::UPDATE => "Редактирование",
            self::DELETE => "Удаление",
            self::PUBLISH => "Изменение публикации",
        ];
    }

    /**
     * Стандартные права.
     *
     * @return int
     */
    public static function defaultRules()
    {
        return self::VIEW_ALL + self::VIEW + self::UPDATE + self::DELETE + self::PUBLISH;
    }

    /**
     * Просмотр всех.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission($this->model, self::VIEW_ALL);
    }

    /**
     * Просмотр.
     *
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermission($this->model, self::VIEW);
    }

    /**
     * Обновление.
     *
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->hasPermission($this->model, self::UPDATE);
    }

    /**
     * Удаление.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermission($this->model, self::DELETE);
    }

    /**
     * Публикация.
     *
     * @param User $user
     * @return bool
     */
    public function publish(User $user)
    {
        return $user->hasPermission($this->model, self::PUBLISH);
    }
}
