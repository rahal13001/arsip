<?php

namespace App\Filament\Resources\ArchivelendingResource\Pages;

use App\Filament\Resources\ArchivelendingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArchivelending extends EditRecord
{
    protected static string $resource = ArchivelendingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
