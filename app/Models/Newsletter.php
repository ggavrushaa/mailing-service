<?php

namespace App\Models;

use App\NewsletterStatusEnum;
use App\Models\NewsletterRecipient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Newsletter extends Model
{
    protected $table = 'newsletters';

    protected $fillable = [
        'title',
        'content',
        'status',
    ];

    protected $casts = [
        'status' => NewsletterStatusEnum::class
    ];

    public function recipients(): HasMany
    {
        return $this->hasMany(NewsletterRecipient::class);
    }
}
