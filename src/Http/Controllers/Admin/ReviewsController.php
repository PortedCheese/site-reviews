<?php

namespace PortedCheese\SiteReviews\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Review;
use Illuminate\Http\Request;
use PortedCheese\SiteReviews\Http\Requests\ReviewStoreRequest;

class ReviewsController extends Controller
{
    const PAGER = 20;

    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(Review::class, "review");
    }

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
                ->paginate(siteconf()->get('reviews', "pager"))
                ->appends($request->input()),
            'query' => $query,
            'per' => self::PAGER,
            'page' => $request->query->get('page', 1) - 1,
            'moderated' => siteconf()->get('reviews', "needModerate"),
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
            'moderated' => siteconf()->get('reviews', "needModerate"),
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
            ->route("admin.reviews.show", ['review' => $review])
            ->with('success', 'Отзыв успешно обновлен');
    }

    /**
     * Изменить модерацию.
     *
     * @param Review $review
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeModerate(Review $review)
    {
        $this->authorize("publish", $review);
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
            ->route("admin.reviews.index")
            ->with("success", "Отзыв успешно удален");
    }
}
