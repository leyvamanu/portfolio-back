<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
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

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('Titulo')->required()->maxLength(255),
                TextInput::make('short_desc')->label('Descripción corta')->required()->maxLength(255),
                Textarea::make('full_desc')->label('Descripción larga')->required()->rows(6),
                Select::make('type')->label('Tipo')->required()->options([
                    'Professional' => 'Professional',
                    'Personal' => 'Personal',
                ]),
                TextInput::make('github')->label('GitHub URL')->url()->maxLength(255),
                TextInput::make('url')->label('Projecto URL')->url()->maxLength(255),
                Select::make('skills')
                    ->label('Habilidades')
                    ->multiple()
                    ->relationship('skills', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->directory('images')
                    ->disk('public')
                    ->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->saveUploadedFileUsing(function ($file, $record) {
                        $path = $file->storeAs('images', $file->getClientOriginalName(), 'public');
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
                ImageColumn::make('image')->label('Imagen')->square(),
                TextColumn::make('title')->label('Titulo')->searchable()->sortable(),
                TextColumn::make('type')->label('Tipo')->sortable(),
                TextColumn::make('short_desc')->label('Descripción')->limit(50),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
