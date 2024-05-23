<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Filament\Resources\MessageResource\RelationManagers;
use App\Models\Message;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Message Details')
                ->schema([
                    TextInput::make('sender')
                    ->afterStateHydrated(function ($state, $component, $record) {
                        if ($record) {
                            $component->state($record->getSenderEmail());
                        }
                    })
                    ->disabled(),
                    TextInput::make('receiver')
                    ->afterStateHydrated(function ($state, $component, $record) {
                        if ($record) {
                            $component->state($record->getReceiverEmail());
                        }
                    })
                    ->disabled(),
                    Textarea::make('message')
                        ->required()
                        ->columnSpan('full')
                        ->readOnly(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender_id')
                ->sortable()
                ->formatStateUsing(function ($state, $record) {
                    return $record->getSenderEmail();
                }),
                TextColumn::make('receiver_id')
                ->sortable()
                ->formatStateUsing(function ($state, $record) {
                    return $record->getReceiverEmail();
                }),
                TextColumn::make('message')->wrap(),
                TextColumn::make('created_at')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessages::route('/'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}
