<?php

namespace App\Notifications;

use App\Models\Newsletter;
use App\Models\NewsletterRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewsletterNotification extends Notification
{
    use Queueable;

    public function __construct(public Newsletter $newsletter)
    {
        //
    }

    public function via(NewsletterRecipient $recipient): array
    {
        return ['mail'];
    }

    public function toMail(NewsletterRecipient $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->newsletter->title)
            ->line($this->newsletter->content);
    }

}
