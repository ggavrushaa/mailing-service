<?php

namespace App\Filament\Resources\Newsletters\RelationManagers;

use Dom\Text;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use App\NewsletterRecipientStatusEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\RelationManagers\RelationManager;

class RecipientsRelationManager extends RelationManager
{
    protected static string $relationship = 'recipients';

    // protected static ?string $relatedResource = NewsletterRecipientResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->defaultSort('id', 'desc')
            ->heading('Recipients')
            ->emptyStateHeading('No Recipients')
            ->emptyStateDescription('Import Recipients')
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),

                TextColumn::make('user.name')
                    ->label('Name'),

                TextColumn::make('user.email')
                    ->label('Email'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(NewsletterRecipientStatusEnum::class),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
