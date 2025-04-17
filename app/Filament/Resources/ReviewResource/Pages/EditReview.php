<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditReview extends EditRecord
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->before(function ($record) {
                if ($record->avatar) {
                    $path = str_replace('/storage/', '', parse_url($record->avatar, PHP_URL_PATH));
                    Storage::disk('public')->delete($path);
                }
            }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['delete_avatar']) && $this->record->avatar) {
            $avatarPath = str_replace('/storage/', '', $this->record->avatar);
            Storage::disk('public')->delete($avatarPath);
            $data['avatar'] = null;
        }
        unset($data['delete_avatar']);

        return $data;
    }
}
