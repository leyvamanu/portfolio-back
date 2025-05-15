<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudyResource\Pages;
use App\Filament\Resources\StudyResource\RelationManagers;
use App\Models\Study;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class StudyResource extends Resource
{
    protected static ?string $model = Study::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('Título')->required()->maxLength(255),
                TextInput::make('specialty')->label('Especialidad')->required()->maxLength(255),
                DatePicker::make('start_date')->label('Fecha de inicio')->required(),
                DatePicker::make('end_date')->label('Fecha de finalización'),
                TextInput::make('institution')->label('Centro')->required()->maxLength(255),
                FileUpload::make('image')
                    ->label('Imagen del proyecto')
                    ->image()
                    ->directory('studies')
                    ->disk('public')
                    ->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->saveUploadedFileUsing(function ($file, $record) {
                        $path = $file->storeAs('studies', $file->getClientOriginalName(), 'public');
                        return Storage::disk('public')->url($path);
                    })
                    ->dehydrated(fn ($state) => filled($state))
                    ->nullable(),
                Checkbox::make('delete_image')
                    ->label('Eliminar imagen actual')
                    ->visible(fn ($get, $record) => filled($record?->image)),
                ViewField::make('image_preview')
                    ->view('forms.components.image-preview')
                    ->label('Imagen actual')
                    ->visible(fn ($get, $record) => filled($record?->image)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Título')->searchable(),
                TextColumn::make('specialty')->label('Especialidad'),
                TextColumn::make('institution')->label('Centro'),
                TextColumn::make('start_date')->label('Inicio')->date(),
                TextColumn::make('end_date')->label('Fin')->date(),
                ImageColumn::make('image')->label('Logo'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        if ($record->image) {
                            $path = str_replace('/storage/', '', parse_url($record->image, PHP_URL_PATH));
                            Storage::disk('public')->delete($path);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->image) {
                                    $path = str_replace('/storage/', '', parse_url($record->image, PHP_URL_PATH));
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
            'index' => Pages\ListStudies::route('/'),
            'create' => Pages\CreateStudy::route('/create'),
            'edit' => Pages\EditStudy::route('/{record}/edit'),
        ];
    }
}
