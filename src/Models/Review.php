<?php

namespace PortedCheese\SiteReviews\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use PortedCheese\SiteReviews\Http\Requests\ReviewStoreAnswerRequest;
use PortedCheese\SiteReviews\Http\Requests\ReviewStoreRequest;
use PortedCheese\SiteReviews\Notifications\ReviewCreateNotification;

class Review extends Model
{
    use Notifiable;

    protected $fillable = [
        'description',
        'from',
        'user_id',
        'review_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (\App\Review $review) {
            if (empty(siteconf()->get('reviews', "needModerate"))) {
                $review->moderated = 1;
            }
        });

        static::created(function (\App\Review $review) {
            if ($review->review_id) {
                $review->answerTo->forgetAnswersCache();
            }
            $review->notify($review->getNotificationClass($review));
        });

        static::updated(function (\App\Review $review) {
            $review->forgetTeaserCache();
            if ($review->review_id) {
                $review->answerTo->forgetAnswersCache();
            }
        });

        static::deleting(function (\App\Review $review) {
            $review->forgetTeaserCache();
            if ($review->review_id) {
                $review->answerTo->forgetAnswersCache();
            }
            else {
                $review->forgetAnswersCache();
            }
        });
    }

    public function getNotificationClass(\App\Review $review)
    {
        return new ReviewCreateNotification($review);
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return siteconf()->get("reviews", "email");
    }

    /**
     * Может быть автор отзыва.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Могут быть ответы.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(\App\Review::class);
    }

    /**
     * Может быть ответом на отзыв.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function answerTo()
    {
        return $this->belongsTo(\App\Review::class, 'review_id');
    }

    /**
     * От кого отзыв.
     *
     * @return mixed
     */
    public function getNameAttribute()
    {
        if ($this->from) {
            return $this->from;
        }
        else {
            return $this->user->full_name;
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return datehelper()->changeTz($value);
    }

    /**
     * Дата создания.
     *
     * @return string
     */
    public function getCreatedHumanAttribute()
    {
        $carbon = Carbon::parse($this->created_at);
        $month = $carbon->localeMonth;
        return $carbon->format("d {$month} Y H:i");
    }

    /**
     * Создание отзыва.
     *
     * @param ReviewStoreRequest $validator
     * @param bool $attr
     * @return array
     */
    public static function requestReviewStore(ReviewStoreRequest $validator, $attr = false)
    {
        if ($attr) {
            return [
                'description.required' => "Поле Текст отзыва обязательно для заполнения",
                'from.required_without' => "Поле Ваше имя обязательно для заполнения",
            ];
        }
        else {
            return [
                'description' => 'bail|required',
                'from' => 'nullable|required_without:user_id',
            ];
        }
    }

    /**
     * Создание ответа.
     * 
     * @param ReviewStoreAnswerRequest $validator
     * @param bool $attr
     * @return array
     */
    public static function requestReviewStoreAnswer(ReviewStoreAnswerRequest $validator, $attr = false)
    {
        if ($attr) {
            return [
                'description.required' => "Поле Текст отзыва обязательно для заполнения",
                'from.required_without' => "Поле Ваше имя обязательно для заполнения",
                'review_id.required' => "Не указан базовый отзыв",
                'review_id.exists' => "Отзыв не найден",
            ];
        }
        else {
            return [
                'description' => 'bail|required',
                'from' => 'nullable|required_without:user_id',
                'review_id' => 'required|exists:reviews,id',
            ];
        }
    }

    /**
     * Получить тизер отзыва.
     *
     * @return string
     * @throws \Throwable
     */
    public function getTeaser()
    {
        $cached = Cache::get("review-teaser:{$this->id}");
        if (!empty($cached)) {
            return $cached;
        }
        $view = view("site-reviews::site.teaser", ['review' => $this]);
        $html = $view->render();
        Cache::forever("review-teaser:{$this->id}", $html);
        return $html;
    }

    /**
     * Ответы.
     *
     * @return array|mixed
     */
    public function getRenderedAnswers()
    {
        $cached = Cache::get("review-answers:{$this->id}");
        if (isset($cached) && is_array($cached)) {
            return $cached;
        }
        $answers = [];
        foreach ($this->answers->where('moderated', 1)->sortBy('created_at') as $answer) {
            $answers[] = $answer->getTeaser();
        }
        Cache::forever("review-answers:{$this->id}", $answers);
        return $answers;
    }

    /**
     * Очистить кэш.
     */
    public function forgetTeaserCache()
    {
        Cache::forget("review-teaser:{$this->id}");
    }

    /**
     * Очистить кэш ответов.
     */
    public function forgetAnswersCache()
    {
        Cache::forget("review-answers:{$this->id}");
    }
}
