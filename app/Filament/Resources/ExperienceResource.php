<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('company')->label('Empresa')->required()->maxLength(255),
                TextInput::make('position')->label('Cargo')->required()->maxLength(255),
                Textarea::make('description')->label('Descripción')->required()->rows(4),
                DatePicker::make('start_date')->label('Fecha de inicio')->required(),
                DatePicker::make('end_date')->label('Fecha de fin')->nullable()->label('Fecha de fin (opcional si sigue en curso)'),
                Select::make('skills')
                    ->label('Habilidades')
                    ->multiple()
                    ->relationship('skills', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),

                FileUpload::make('logo')
                    ->label('Logo de la empresa')
                    ->image()
                    ->directory('logos')
                    ->disk('public')->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->saveUploadedFileUsing(function ($file, $record) {
                        $path = $file->storeAs('logos', $file->getClientOriginalName(), 'public');
                        return Storage::disk('public')->url($path);
                    })
                    ->dehydrated(fn ($state) => filled($state))
                    ->nullable(),
                Checkbox::make('delete_logo')
                    ->label('Eliminar logo actual')
                    ->visible(fn ($get, $record) => filled($record?->logo)),
                ViewField::make('logo_preview')
                    ->view('forms.components.logo-preview')
                    ->label('Logo actual')
                    ->visible(fn ($get, $record) => filled($record?->logo)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->label('Logo')->square(),
                TextColumn::make('company')->label('Empresa')->searchable(),
                TextColumn::make('position')->label('Cargo')->searchable(),
                TextColumn::make('start_date')->label('Inicio')->date(),
                TextColumn::make('end_date')->label('Fin')->date()->default('Actualidad'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        if ($record->logo) {
                            $path = str_replace('/storage/', '', parse_url($record->logo, PHP_URL_PATH));
                            Storage::disk('public')->delete($path);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->logo) {
                                    $path = str_replace('/storage/', '', parse_url($record->logo, PHP_URL_PATH));
                                    Storage::disk('public')->delete($path);
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExperiences::route('/'),
            'create' => Pages\CreateExperience::route('/create'),
            'edit' => Pages\EditExperience::route('/{record}/edit'),
        ];
    }
}
