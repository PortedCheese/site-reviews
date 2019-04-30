<?php

namespace PortedCheese\SiteReviews\Listeners;


use Illuminate\Support\Facades\Log;
use PortedCheese\SiteReviews\Models\Review;

class UserUpdateClearCache
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $userId = $event->user->id;
        Log::info($userId);
        $reviews = Review::where('user_id', $userId)->get();
        foreach ($reviews as $review) {
            $review->forgetTeaserCache();
        }
    }
}
