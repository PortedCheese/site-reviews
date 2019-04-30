<?php

namespace PortedCheese\SiteReviews\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use PortedCheese\SiteReviews\Http\Requests\ReviewStoreAnswerRequest;
use PortedCheese\SiteReviews\Http\Requests\ReviewStoreRequest;
use Illuminate\Http\Request;
use PortedCheese\SeoIntegration\Models\Meta;
use PortedCheese\SiteReviews\Models\Review;

class ReviewsController extends Controller
{
    /**
     * Страница отзывов.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view("site-reviews::site.index", [
            'pageMetas' => Meta::getByPageKey('reviews'),
            'customTheme' => siteconf()->get('reviews.customTheme'),
        ]);
    }

    /**
     * Сохранение отзыва.
     */
    public function store(ReviewStoreRequest $request)
    {
        Review::create($request->all());
        if (siteconf()->get('reviews.needModerate')) {
            $message = "Ваш отзыв получен, он появится на сайте после модерации администратором";
        }
        else {
            $message = "Ваш отзыв получен";
        }
        return [
            'message' => $message,
        ];
    }

    /**
     * Сохранить ответ.
     *
     * @param ReviewStoreAnswerRequest $request
     * @return array
     */
    public function storeAnswer(ReviewStoreAnswerRequest $request)
    {
        Review::create($request->all());
        if (siteconf()->get('reviews.needModerate')) {
            $message = "Ваш отзыв получен, он появится на сайте после модерации администратором";
        }
        else {
            $message = "Ваш отзыв получен";
        }
        return [
            'message' => $message,
        ];
    }

    /**
     * Вернуть список отзывов.
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $reviews = Review::query()
            ->where('moderated', 1)
            ->whereNull('review_id')
            ->orderBy('created_at', 'desc')
            ->paginate(siteconf()->get('reviews.pager'))
            ->appends($request->input());
        $rendered = [];
        foreach ($reviews as $review) {
            $rendered[] = [
                'html' => $review->getTeaser(),
                'review' => $review,
                'answers' => $review->getRenderedAnswers(),
            ];
        }
        return [
            'pagesInfo' => $reviews,
            'rendered' => $rendered,
        ];
    }
}
