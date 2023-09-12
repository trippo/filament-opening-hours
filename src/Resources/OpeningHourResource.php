<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\OpeningHours\Resources;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maartenpaauw\Filament\OpeningHours\Enums\Day;
use Maartenpaauw\Filament\OpeningHours\Models\OpeningHour;
use Maartenpaauw\Filament\OpeningHours\Resources\OpeningHourResource\Pages\CreateOpeningHour;
use Maartenpaauw\Filament\OpeningHours\Resources\OpeningHourResource\Pages\EditOpeningHour;
use Maartenpaauw\Filament\OpeningHours\Resources\OpeningHourResource\Pages\ListOpeningHours;
use Maartenpaauw\Filament\OpeningHours\Resources\OpeningHourResource\Pages\ViewOpeningHour;

final class OpeningHourResource extends Resource
{
    protected static ?string $model = OpeningHour::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->minLength(1)
                    ->maxLength(255),
                Repeater::make('days')
                    ->relationship()
                    ->translateLabel()
                    ->schema([
                        Select::make('day')
                            ->translateLabel()
                            ->required()
                            ->options(Day::class),
                        Repeater::make('timeRanges')
                            ->translateLabel()
                            ->relationship()
                            ->required()
                            ->columns(2)
                            ->schema([
                                TimePicker::make('start')
                                    ->translateLabel()
                                    ->inlineLabel()
                                    ->seconds(false)
                                    ->required(),
                                TimePicker::make('end')
                                    ->translateLabel()
                                    ->inlineLabel()
                                    ->seconds(false)
                                    ->required(),
                            ]),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOpeningHours::route('/'),
            'create' => CreateOpeningHour::route('/create'),
            'view' => ViewOpeningHour::route('/{record}'),
            'edit' => EditOpeningHour::route('/{record}/edit'),
        ];
    }
}
