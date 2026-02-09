<?php
namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum NewsletterStatusEnum: string implements HasColor, HasLabel
{
    case draft = 'draft';
    case sending = 'sending';
    case completed = 'completed';
    case stopped = 'stopped';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::draft => 'Draft',
            self::sending => 'Sending',
            self::completed => 'Completed',
            self::stopped => 'Stopped',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::draft => 'secondary',
            self::sending => 'warning',
            self::completed => 'success',
            self::stopped => 'danger',
        };
    }
}
