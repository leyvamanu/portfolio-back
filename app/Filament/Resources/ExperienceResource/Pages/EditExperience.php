<?php

namespace App\Filament\Resources\ExperienceResource\Pages;

use App\Filament\Resources\ExperienceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditExperience extends EditRecord
{
    protected static string $resource = ExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->before(function ($record) {
                if ($record->logo) {
                    $path = str_replace('/storage/', '', parse_url($record->logo, PHP_URL_PATH));
                    Storage::disk('public')->delete($path);
                }
            }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['delete_logo']) && $this->record->logo) {
            $logoPath = str_replace('/storage/', '', $this->record->logo);
            Storage::disk('public')->delete($logoPath);
            $data['logo'] = null;
        }
        unset($data['delete_logo']);

        return $data;
    }
}
