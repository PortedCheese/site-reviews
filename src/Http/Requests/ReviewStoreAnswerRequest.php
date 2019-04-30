<?php

namespace PortedCheese\SiteReviews\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'bail|required',
            'from' => 'nullable|required_without:user_id',
            'review_id' => 'required|exists:reviews,id',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => "Поле Текст отзыва обязательно для заполнения",
            'from.required_without' => "Поле Ваше имя обязательно для заполнения",
            'review_id.required' => "Не указан базовый отзыв",
            'review_id.exists' => "Отзыв не найден",
        ];
    }
}
