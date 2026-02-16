<?php

namespace App\Jobs;

use Throwable;
use App\Models\Newsletter;
use App\Models\NewsletterRecipient;
use App\NewsletterRecipientStatusEnum;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\NewsletterNotification;
use Illuminate\Bus\Batchable;

class SendNewsletterRecipientJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public int $tries = 3;

    public function __construct(
        public Newsletter $newsletter,
        public NewsletterRecipient $recipient
    ) {
        $this->onQueue('newsletter:send');
    }

    public function handle(): void
    {
        if ($this->newsletter->status->isStopped()) {
            return;
        }

        // $notification = new NewsletterNotification($this->newsletter);

        // $this->recipient->notifyNow($notification);

        $this->recipient->update([
            'status' => NewsletterRecipientStatusEnum::success->value
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        $this->recipient->update([
            'status' => NewsletterRecipientStatusEnum::failed->value
        ]);

        $this->recipient->save();
    }
}
