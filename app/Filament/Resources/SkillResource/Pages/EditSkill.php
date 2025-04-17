<?php

namespace App\Filament\Resources\SkillResource\Pages;

use App\Filament\Resources\SkillResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditSkill extends EditRecord
{
    protected static string $resource = SkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->before(function ($record) {
                if ($record->icon) {
                    $path = str_replace('/storage/', '', parse_url($record->icon, PHP_URL_PATH));
                    Storage::disk('public')->delete($path);
                }
            }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['delete_icon']) && $this->record->icon) {
            $iconPath = str_replace('/storage/', '', $this->record->icon);
            Storage::disk('public')->delete($iconPath);
            $data['icon'] = null;
        }
        unset($data['delete_icon']);

        return $data;
    }
}
