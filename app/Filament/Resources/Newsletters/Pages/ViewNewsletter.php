<?php

namespace App\Filament\Resources\Newsletters\Pages;

use App\Filament\Resources\Newsletters\NewsletterResource;
use App\Jobs\ImportNewsletterRecipientJob;
use App\Jobs\SendImportNewsletterRecipientNotificationJob;
use App\Models\Newsletter;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Bus;

class ViewNewsletter extends ViewRecord
{
    protected static string $resource = NewsletterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->color('gray')
                ->label('Import')
                ->requiresConfirmation()
                ->successNotificationTitle('Import was started')
                ->action(function (Action $action, Newsletter $record) {
                    $admin = auth()->user();
                    
                    Bus::chain([
                        new ImportNewsletterRecipientJob($record),
                        new SendImportNewsletterRecipientNotificationJob($record, $admin),
                    ])->dispatch();

                    $action->success();
                }),
            ];
    }
}
