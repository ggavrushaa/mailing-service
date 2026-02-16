<?php

namespace App\Filament\Resources\Newsletters\Pages;

use App\Models\Newsletter;
use Filament\Actions\Action;
use App\NewsletterStatusEnum;
use App\Jobs\StartNewsletterJob;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Facades\Bus;
use Filament\Resources\Pages\ViewRecord;
use App\Jobs\ImportNewsletterRecipientJob;
use App\Filament\Resources\Newsletters\NewsletterResource;
use App\Jobs\SendImportNewsletterRecipientNotificationJob;
use App\Filament\Resources\Newsletters\Widgets\NewsletterOverviewWidget;
use Illuminate\Contracts\Support\Htmlable;

class ViewNewsletter extends ViewRecord
{
    protected static string $resource = NewsletterResource::class;

    public function getSubheading(): ?string
    {
        return implode(' • ', [
            'ID' => $this->record->id,
            $this->record->status->getLabel(),
            $this->record->created_at->diffForHumans(),
        ]);
    }

    public function getHeaderWidgets(): array
    {
        return [
            NewsletterOverviewWidget::make(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([

                EditAction::make()
                    ->label('Edit newsletter')
                    ->color('gray'),

                Action::make('send')
                    ->color('gray')
                    ->visible(fn(Newsletter $record) => ! $record->status->isSending())
                    ->icon('heroicon-m-paper-airplane')
                    ->label('Send newsletters')
                    ->requiresConfirmation()
                    ->successNotificationTitle('Import was started')
                    ->action(function (Action $action, Newsletter $record) {
                        $batch = Bus::batch([])->name('newsletter:' . $record->id)
                            ->onQueue('newsletter')
                            ->allowFailures()
                            ->finally(fn() => $record->update(['status' => NewsletterStatusEnum::completed->value]))
                            ->dispatch();

                        $record->update([
                            'status' => NewsletterStatusEnum::sending,
                            'batch_id' => $batch->id,
                        ]);

                        dispatch(new StartNewsletterJob($record));

                        $action->success();
                    }),

                Action::make('stop')
                    ->label('Stop sending')
                    ->icon('heroicon-m-stop-circle')
                    ->color('danger')
                    ->visible(fn(Newsletter $record) => $record->status->isSending())
                    ->requiresConfirmation()
                    ->successNotificationTitle('Newsletter sending was stopped')
                    ->action(function (Action $action, Newsletter $record) {
                        $record->update(['status' => NewsletterStatusEnum::stopped]);
                        $action->success();
                    }),

                Action::make('import')
                    ->color('gray')
                    ->icon('heroicon-o-document-arrow-down')
                    ->label('Import recipients')
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

            ])->button()->color('gray'),
        ];
    }
}
