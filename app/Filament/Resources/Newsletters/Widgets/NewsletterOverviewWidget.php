<?php

namespace App\Filament\Resources\Newsletters\Widgets;

use App\Models\Newsletter;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use App\Models\NewsletterRecipient;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class NewsletterOverviewWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '5s';

    public ?Newsletter $record = null;

     public function mount(Model $record): void
    {
        $this->record = $record;
    }
    protected function getStats(): array
    {
        $stats = NewsletterRecipient::query()
            ->where('newsletter_id', $this->record->id)
            ->addSelect(DB::raw('COUNT(*) as total_count'))
            ->addSelect(DB::raw("COUNT(CASE WHEN status = 'success' THEN 1 END) as sent_count"))
            ->addSelect(DB::raw("COUNT(CASE WHEN status = 'failed' THEN 1 END) as failed_count"))
            ->first();
            
        return [
            Stat::make('Total recipients', Number::format($stats->total_count ?: 0)),
            Stat::make('Sent', Number::format($stats->sent_count ?: 0)),
            Stat::make('Failed', Number::format($stats->failed_count ?: 0)),
        ];
    }
}
