<?php

namespace PortedCheese\SiteReviews\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Review;

class ReviewCreateNotification extends Notification
{
    use Queueable;

    protected $review;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $form = $this->review->name;
        return (new MailMessage)
            ->subject("Новый отзыв от {$form}")
            ->greeting('Здравствуйте!')
            ->line('На сайте был оставлен новый отзыв.')
            ->line($this->review->description)
            ->action(
                'Просмотр',
                route('admin.reviews.show', ['review' => $this->review])
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
