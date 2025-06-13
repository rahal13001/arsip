<?php

namespace App\Filament\Guest\Resources\ArchiveResource\Pages;

use App\Filament\Guest\Resources\ArchiveResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewArchive extends ViewRecord
{
    protected static string $resource = ArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('lending')
                ->label('Pinjam Arsip')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn (): string => $this->getResource()::getUrl('index') . '/' . $this->record->getRouteKey() . '/lending'),

        ];
    }
}
