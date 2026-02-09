<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Newsletter;
use App\Models\NewsletterRecipient;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendImportNewsletterRecipientNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Newsletter $newsletter,
        public User $admin
    )
    {
        $this->onQueue('newsletter');
    }

    public function handle(): void
    {
        Notification::make()
            ->title('Newsletter Recipient Imported')
            ->sendToDatabase($this->admin);
    }
}
