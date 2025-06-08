<?php

namespace App\Filament\Resources\AccessclassificationResource\Pages;

use App\Filament\Resources\AccessclassificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAccessclassifications extends ManageRecords
{
    protected static string $resource = AccessclassificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
