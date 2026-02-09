<?php
namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum NewsletterRecipientStatusEnum: string implements HasColor, HasLabel
{
    case pending = 'pending';
    case success = 'success';
    case failed = 'failed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::pending => 'Pending',
            self::success => 'Success',
            self::failed => 'Failed',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::pending => 'warning',
            self::success => 'success',
            self::failed => 'danger',
        };
    }
}
