<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Newsletter;
use Illuminate\Pagination\Cursor;
use App\Models\NewsletterRecipient;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;


class ImportNewsletterRecipientJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Newsletter $newsletter,
        public ?Cursor $nextCursor = null
    ) {
        $this->onQueue('newsletter');
    }

    public function tags(): array
    {
        return [
            'newsletter:import',
            "newsletter:{$this->newsletter->id}",
        ];
    }

    public function handle(): void
    {
        $users = User::query()
            ->oldest('id')
            ->cursorPaginate(
                cursor: $this->nextCursor,
                perPage: 1000
            );

        $recipients = [];

        foreach ($users as $user) {
            $recipients[] = [
                'newsletter_id' => $this->newsletter->id,
                'user_id' => $user->id,
            ];
        }

        NewsletterRecipient::query()->upsert(
            $recipients,
            ['newsletter_id', 'user_id']
        );

        if ($users->hasMorePages()) {
            $this->prependToChain(new ImportNewsletterRecipientJob(
                newsletter: $this->newsletter,
                nextCursor: $users->nextCursor(),
            ));
        }
    }
}
