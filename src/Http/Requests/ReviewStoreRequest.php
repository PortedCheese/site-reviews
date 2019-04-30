<?php

namespace PortedCheese\SiteReviews\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'description.required' => "Поле Текст отзыва обязательно для заполнения",
            'from.required_without' => "Поле Ваше имя обязательно для заполнения",
        ];
    }
}
