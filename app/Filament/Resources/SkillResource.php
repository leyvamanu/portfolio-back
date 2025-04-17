<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SkillResource\Pages;
use App\Models\Skill;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(255),
                FileUpload::make('icon')
                    ->image()
                    ->directory('icons')
                    ->disk('public')
                    ->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->saveUploadedFileUsing(function ($file, $record) {
                        $path = $file->storeAs('icons', $file->getClientOriginalName(), 'public');
                        return Storage::disk('public')->url($path);
                    })
                    ->dehydrated(fn ($state) => filled($state))
                    ->nullable(),
                Checkbox::make('delete_icon')
                    ->label('Eliminar icono actual')
                    ->visible(fn ($get, $record) => filled($record?->icon)),
                ViewField::make('icon_preview')
                    ->view('forms.components.icon-preview')
                    ->label('Icono actual')
                    ->visible(function ($get, $set, $state, $record) {
                        return filled($record?->icon);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('icon')->label('Icono')->url(fn ($record) => $record->icon)->circular(),
                TextColumn::make('name')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        if ($record->icon) {
                            $path = str_replace('/storage/', '', parse_url($record->icon, PHP_URL_PATH));
                            Storage::disk('public')->delete($path);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->icon) {
                                    $path = str_replace('/storage/', '', parse_url($record->icon, PHP_URL_PATH));
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
            'index' => Pages\ListSkills::route('/'),
            'create' => Pages\CreateSkill::route('/create'),
            'edit' => Pages\EditSkill::route('/{record}/edit'),
        ];
    }
}
