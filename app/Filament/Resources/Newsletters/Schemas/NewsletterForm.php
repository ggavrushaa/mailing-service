<?php

namespace App\Filament\Resources\Newsletters\Schemas;

use Dom\Text;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsletterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required(),

                        MarkdownEditor::make('content')
                            ->label('Content')
                            ->required(),
                    ]),
            ]);
    }
}
