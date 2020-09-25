<?php

namespace PortedCheese\SiteReviews\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Meta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
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

        if (base_config()->get('reviews', "needModerate")) {
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
        if (base_config()->get('reviews', "needModerate")) {
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
     * @throws \Throwable
     */
    public function list(Request $request)
    {
        $reviews = Review::query()
            ->whereNotNull('moderated_at')
            ->whereNull('review_id')
            ->orderBy('registered_at', 'desc')
            ->paginate(base_config()->get('reviews', "pager"))
            ->appends($request->input());
        $rendered = [];
        foreach ($reviews as $review) {
            /**
             * @var Review $review
             */
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
