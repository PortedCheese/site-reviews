<?php

namespace PortedCheese\SiteReviews\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            $reviews->whereNull('moderated_at');
        }
        $reviews->orderBy('registered_at', 'desc');
        return view("site-reviews::admin.index", [
            'reviews' => $reviews
                ->paginate(base_config()->get('reviews', "pager"))
                ->appends($request->input()),
            'query' => $query,
            'per' => self::PAGER,
            'page' => $request->query->get('page', 1) - 1,
            'moderated' => base_config()->get('reviews', "needModerate"),
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
            'moderated' => base_config()->get('reviews', "needModerate"),
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
     * @param Request $request
     * @param Review $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Review $review)
    {
        $this->updateValidator($request->all());
        $review->update($request->all());
        return redirect()
            ->route("admin.reviews.show", ['review' => $review])
            ->with('success', 'Отзыв успешно обновлен');
    }

    protected function updateValidator($data)
    {
        Validator::make($data, [
            "description" => ["required"],
            "registered_at" => ["required", "date"],
        ], [], [
            "description" => "Текст отзыва",
            "registered_at" => "Дата отзыва",
        ])->validate();
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
        $review->moderated_at = empty($review->moderated_at) ? now() : null;
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
