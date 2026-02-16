<?php

namespace App\Filament\Resources\Newsletters;

use BackedEnum;
use App\Models\Newsletter;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Newsletters\Pages\EditNewsletter;
use App\Filament\Resources\Newsletters\Pages\ViewNewsletter;
use App\Filament\Resources\Newsletters\Pages\ListNewsletters;
use App\Filament\Resources\Newsletters\Pages\CreateNewsletter;
use App\Filament\Resources\Newsletters\RelationManagers\RecipientsRelationManager;
use App\Filament\Resources\Newsletters\Schemas\NewsletterForm;
use App\Filament\Resources\Newsletters\Tables\NewslettersTable;
use App\Filament\Resources\Newsletters\Schemas\NewsletterInfolist;
use App\Filament\Resources\Newsletters\Widgets\NewsletterOverviewWidget;

class NewsletterResource extends Resource
{
    protected static ?string $model = Newsletter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::EnvelopeOpen;

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Newsletter';

    protected static ?string $pluralModelLabel = 'Newsletters';

    public static function form(Schema $schema): Schema
    {
        return NewsletterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NewsletterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewslettersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RecipientsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsletters::route('/'),
            'create' => CreateNewsletter::route('/create'),
            'view' => ViewNewsletter::route('/{record}'),
            'edit' => EditNewsletter::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            NewsletterOverviewWidget::class,
        ];
    }
}
