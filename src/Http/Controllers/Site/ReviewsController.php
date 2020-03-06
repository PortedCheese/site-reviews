<?php

namespace PortedCheese\SiteReviews\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use PortedCheese\SeoIntegration\Models\Meta;
use App\Review;

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
        ]);
    }

    /**
     * Сохранение отзыва.
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->storeValidator($request->all());

        Review::create($request->all());

        if (siteconf()->get('reviews', "needModerate")) {
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
     * Валидация добавления.
     *
     * @param $data
     */
    private function storeValidator($data)
    {
        Validator::make($data, [
            "description" => ["bail", "required"],
            "from" => ["nullable", "required_without:user_id"],
        ], [
            'description.required' => "Поле Текст отзыва обязательно для заполнения",
            'from.required_without' => "Поле Ваше имя обязательно для заполнения",
        ], [
            "description" => "Текст отзыва",
            "from" => "Ваше имя",
        ])->validate();
    }

    /**
     * Сохранить ответ.
     *
     * @param Request $request
     * @return array
     */
    public function storeAnswer(Request $request)
    {
        $this->storeAnswerValidator($request->all());
        Review::create($request->all());
        if (siteconf()->get('reviews', "needModerate")) {
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
     * Валидация ответа.
     *
     * @param $data
     */
    private function storeAnswerValidator($data)
    {
        Validator::make($data, [
            'description' => 'bail|required',
            'from' => 'nullable|required_without:user_id',
            'review_id' => 'required|exists:reviews,id',
        ], [
            'description.required' => "Поле Текст отзыва обязательно для заполнения",
            'from.required_without' => "Поле Ваше имя обязательно для заполнения",
            'review_id.required' => "Не указан базовый отзыв",
            'review_id.exists' => "Отзыв не найден",
        ], [
            "description" => "Текст отзыва",
            "from" => "Ваше имя",
            "review_id" => "Отзыв",
        ])->validate();
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
            ->paginate(siteconf()->get('reviews', "pager"))
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
