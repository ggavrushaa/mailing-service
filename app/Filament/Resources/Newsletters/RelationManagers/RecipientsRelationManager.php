<?php

namespace App\Filament\Resources\Newsletters\RelationManagers;

use App\Filament\Resources\Newsletters\NewsletterResource;
use Dom\Text;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecipientsRelationManager extends RelationManager
{
    protected static string $relationship = 'recipients';

    // protected static ?string $relatedResource = NewsletterRecipientResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recipients')
            ->emptyStateHeading('No Recipients')
            ->emptyStateDescription('Import Recipients')
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
            ]);
    }
}
