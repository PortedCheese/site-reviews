<?php

namespace PortedCheese\SiteReviews\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PortedCheese\SiteReviews\Http\Requests\ReviewsSettingsRequest;
use PortedCheese\SiteReviews\Http\Requests\ReviewStoreRequest;
use PortedCheese\SiteReviews\Models\Review;

class ReviewsController extends Controller
{
    const PAGER = 20;

    /**
     * Все отзывы.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->query;
        $reviews = Review::query();
        if ($this->routeName == "admin.reviews.need-moderate") {
            $reviews->where('moderated', 0);
        }
        $reviews->orderBy('created_at', 'desc');
        return view("site-reviews::admin.index", [
            'reviews' => $reviews
                ->paginate(siteconf()->get('reviews.pager'))
                ->appends($request->input()),
            'query' => $query,
            'per' => self::PAGER,
            'page' => $request->query->get('page', 1) - 1,
            'moderated' => siteconf()->get('reviews.needModerate'),
        ]);
    }

    /**
     * Просмотр.
     *
     * @param Review $review
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Review $review)
    {
        return view("site-reviews::admin.show", [
            'review' => $review,
            'moderated' => siteconf()->get('reviews.needModerate'),
        ]);
    }

    /**
     * Редактирование.
     *
     * @param Review $review
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Review $review)
    {
        return view("site-reviews::admin.edit", [
            'review' => $review,
        ]);
    }

    /**
     * Обновление.
     *
     * @param ReviewStoreRequest $request
     * @param Review $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReviewStoreRequest $request, Review $review)
    {
        $review->update($request->all());
        return redirect()
            ->route("admin.reviews.index")
            ->with('success', 'Отзыв успешно обновлен');
    }

    /**
     * Изменить модерацию.
     *
     * @param Review $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeModerate(Review $review)
    {
        $review->moderated = !$review->moderated;
        $review->save();
        return redirect()
            ->back()
            ->with('success', 'Статус модерации изменен.');
    }

    /**
     * Удаление отзыва.
     *
     * @param Review $review
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Review $review)
    {
        if ($review->answers->count()) {
            return redirect()
                ->back()
                ->with("danger", "Невозможно удалить, у этого отзыва есть ответы");
        }
        $review->delete();
        return redirect()
            ->back()
            ->with("success", "Отзыв успешно удален");
    }

    /**
     * Настройки.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings()
    {
        $config = siteconf()->get('reviews');
        $themes = config('theme.themes');
        return view("site-reviews::admin.settings", [
            'config' => (object) $config,
            'themes' => $themes,
        ]);
    }

    /**
     * Сохранить настройки.
     *
     * @param ReviewsSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveSettings(ReviewsSettingsRequest $request)
    {
        $config = siteconf()->get('reviews');
        foreach ($config as $key => $value) {
            if ($request->has($key)) {
                $config[$key] = $request->get($key);
            }
        }
        if ($request->has('theme')) {
            $config['customTheme'] = $request->get('theme');
        }
        else {
            $config['customTheme'] = null;
        }
        if ($request->has('moderation')) {
            $config['needModerate'] = true;
        }
        else {
            $config['needModerate'] = false;
        }
        siteconf()->save('reviews', $config);
        return redirect()
            ->back()
            ->with('success', "Конфигурация обновлена");
    }
}
