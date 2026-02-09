<?php

namespace App\Models;

use App\NewsletterRecipientStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsletterRecipient extends Model
{
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

}
