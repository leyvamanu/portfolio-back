<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('role')->maxLength(255),
                Select::make('rating')
                    ->required()
                    ->options([
                        1 => '★☆☆☆☆',
                        2 => '★★☆☆☆',
                        3 => '★★★☆☆',
                        4 => '★★★★☆',
                        5 => '★★★★★',
                    ]),
                Textarea::make('content')->required(),
                FileUpload::make('avatar')
                    ->image()
                    ->directory('avatars')
                    ->disk('public')
                    ->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->saveUploadedFileUsing(function ($file, $record) {
                        $path = $file->storeAs('avatars', $file->getClientOriginalName(), 'public');
                        return Storage::disk('public')->url($path);
                    })
                    ->dehydrated(fn ($state) => filled($state))
                    ->nullable(),
                Checkbox::make('delete_avatar')
                    ->label('Eliminar avatar actual')
                    ->visible(fn ($get, $record) => filled($record?->avatar)),
                ViewField::make('avatar_preview')
                    ->view('forms.components.avatar-preview')
                    ->label('Avatar actual')
                    ->visible(function ($get, $set, $state, $record) {
                        return filled($record?->avatar);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')->label('Avatar')->url(fn ($record) => $record->avatar)->circular(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('role')->label('Cargo'),
                TextColumn::make('rating')->label('Puntuación'),
                TextColumn::make('content')->limit(20)->label('Comentario'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        if ($record->avatar) {
                            $path = str_replace('/storage/', '', parse_url($record->avatar, PHP_URL_PATH));
                            Storage::disk('public')->delete($path);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->before(function ($records) {
                        foreach ($records as $record) {
                            if ($record->avatar) {
                                $path = str_replace('/storage/', '', parse_url($record->avatar, PHP_URL_PATH));
                                Storage::disk('public')->delete($path);
                            }
                        }
                    }),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
