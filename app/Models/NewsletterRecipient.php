<?php

namespace App\Models;

use App\NewsletterRecipientStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class NewsletterRecipient extends Model
{
    use Notifiable;

    protected $table = 'newsletter_recipients';

    protected $fillable = [
        'newsletter_id',
        'user_id',
        'status',
    ];

    protected $casts = [
        'status' => NewsletterRecipientStatusEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function routeNotificationForMail(): array
    {
        return [$this->user->email => $this->user->name];
    }

}
