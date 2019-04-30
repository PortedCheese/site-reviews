<?php

namespace PortedCheese\SiteReviews\Models;

use App\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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

        static::creating(function (Review $review) {
            if (empty(siteconf()->get('reviews.needModerate'))) {
                $review->moderated = 1;
            }
        });

        static::created(function (Review $review) {
            if ($review->review_id) {
                $review->answerTo->forgetAnswersCache();
            }
            $review->notify(new ReviewCreateNotification($review));
        });

        static::updated(function (Review $review) {
            $review->forgetTeaserCache();
            if ($review->review_id) {
                $review->answerTo->forgetAnswersCache();
            }
        });

        static::deleting(function (Review $review) {
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
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return siteconf()->get('reviews.email');
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
        return $this->hasMany(Review::class);
    }

    /**
     * Может быть ответом на отзыв.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function answerTo()
    {
        return $this->belongsTo(Review::class, 'review_id');
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
        foreach ($this->answers->sortBy('created_at') as $answer) {
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
