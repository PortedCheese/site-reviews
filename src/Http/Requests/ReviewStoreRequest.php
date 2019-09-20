<?php

namespace PortedCheese\SiteReviews\Http\Requests;

use App\Review;
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
        return Review::requestReviewStore($this);
    }

    public function messages()
    {
        return Review::requestReviewStore($this, true);
    }
}
