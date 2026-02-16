<?php

namespace App\Jobs;

use App\Models\Newsletter;
use App\Models\NewsletterRecipient;
use App\NewsletterRecipientStatusEnum;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Contracts\Queue\ShouldQueue;

class StartNewsletterJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function __construct(
        public Newsletter $newsletter,
        private ?Cursor $nextCursor = null
    ) {
        $this->onQueue('newsletter');
    }

    public function tags(): array
    {
        return [
            'newsletter:start',
            "newsletter:{$this->newsletter->id}"
        ];
    }

    public function handle(): void
    {
        if ($this->newsletter->status->isStopped()) {
            return;
        }

        /** @var CursorPaginator */
        $recipients = NewsletterRecipient::query()
            ->where('newsletter_id', $this->newsletter->id)
            ->where('status', NewsletterRecipientStatusEnum::pending->value)
            ->oldest('id')
            ->cursorPaginate(
                perPage: 1000,
                cursor: $this->nextCursor,
            );

        $jobs = [];

        foreach ($recipients as $recipient) {
            $jobs[] = new SendNewsletterRecipientJob($this->newsletter, $recipient);
        }

        $this->newsletter->batch->add($jobs);

        if ($recipients->hasMorePages()) {
            dispatch(new StartNewsletterJob(
                $this->newsletter,
                $recipients->nextCursor()
            ));
        }
    }
}
