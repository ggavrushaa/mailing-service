<?php

namespace App\Models;

use Illuminate\Bus\Batch;
use App\NewsletterStatusEnum;
use App\Models\NewsletterRecipient;
use Illuminate\Support\Facades\Bus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @property-read Batch $batch */
class Newsletter extends Model
{
    protected $table = 'newsletters';

    protected $fillable = [
        'title',
        'content',
        'status',
        'batch_id',
    ];

    protected $casts = [
        'status' => NewsletterStatusEnum::class
    ];

    public function recipients(): HasMany
    {
        return $this->hasMany(NewsletterRecipient::class);
    }

    public function batch(): Attribute
    {
        return Attribute::get(function () {
            return Bus::findBatch($this->batch_id);
        });
    }
}
