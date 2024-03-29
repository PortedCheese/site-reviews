<?php

namespace PortedCheese\SiteReviews\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use PortedCheese\SiteReviews\Notifications\ReviewCreateNotification;

class Review extends Model
{
    use Notifiable;

    protected $fillable = [
        "description",
        "from",
        "user_id",
        "review_id",
        "registered_at",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (\App\Review $review) {
            $review->registered_at = now("Europe/Moscow");
            if (empty(base_config()->get('reviews', "needModerate"))) {
                $review->moderated_at = now();
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

    /**
     * Класс для уведомлений.
     *
     * @param \App\Review $review
     * @return ReviewCreateNotification
     */
    public function getNotificationClass(\App\Review $review)
    {
        return new ReviewCreateNotification($review);
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @return array
     */

    public function routeNotificationForMail($notification)
    {
        return $this->emailsToArray();
    }

    private function emailsToArray() {
        //perform more checks that you need
        return array_map('trim', explode(',', base_config()->get("reviews", "email")));
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

    /**
     * Добавлен.
     *
     * @param $value
     * @return mixed
     */
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
     * Дата создания.
     *
     * @return string
     */
    public function getRegisteredHumanAttribute()
    {
        return datehelper()->format($this->registered_at, "d.m.Y");
    }

    /**
     * Получить тизер отзыва.
     *
     * @return string
     * @throws \Throwable
     */
    public function getTeaser()
    {
        $key = "review-teaser:{$this->id}";
        $review = $this;
        $data = Cache::rememberForever($key, function () use ($review) {
            $user = $review->user;
            if ($user) {
                $avatar = $user->avatar;
            }
            else {
                $avatar = false;
            }
            return [
                "user" => $user,
                "avatar" => $avatar,
                "review" => $review,
            ];
        });
        $view = view("site-reviews::site.teaser", $data);
        return $view->render();
    }

    /**
     * Ответы.
     *
     * @return array|mixed
     * @throws \Throwable
     */
    public function getRenderedAnswers()
    {
        $cached = Cache::get("review-answers:{$this->id}");
        if (isset($cached) && is_array($cached)) {
            return $cached;
        }
        $answers = [];
        $collection = $this->answers
            ->whereNotNull('moderated_at')
            ->sortBy('created_at');
        foreach ($collection as $answer) {
            /**
             * @var \App\Review $answer
             */
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
