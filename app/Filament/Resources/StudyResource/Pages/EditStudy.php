<?php

namespace App\Filament\Resources\StudyResource\Pages;

use App\Filament\Resources\StudyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditStudy extends EditRecord
{
    protected static string $resource = StudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->before(function ($record) {
                if ($record->image) {
                    $path = str_replace('/storage/', '', parse_url($record->image, PHP_URL_PATH));
                    Storage::disk('public')->delete($path);
                }
            }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['delete_image']) && $this->record->image) {
            $imagePath = str_replace('/storage/', '', $this->record->image);
            Storage::disk('public')->delete($imagePath);
            $data['image'] = null;
        }
        unset($data['delete_image']);

        return $data;
    }
}
