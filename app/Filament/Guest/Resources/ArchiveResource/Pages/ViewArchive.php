<?php

namespace App\Filament\Guest\Resources\ArchiveResource\Pages;

use App\Filament\Guest\Resources\ArchiveResource;
use App\Models\Archivelending;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewArchive extends ViewRecord
{
    protected static string $resource = ArchiveResource::class;

    protected function getHeaderActions(): array
    {
        $check = Archivelending::where('archive_id', $this->record->id)
                    ->where('lending_approval', 1)
                    ->where('return_date', null)
                    ->count();

        return [
            Action::make('lending')
                ->hidden($check > 0)
                ->label('Pinjam Arsip')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn (): string => $this->getResource()::getUrl('index') . '/' . $this->record->getRouteKey() . '/pinjam-arsip'),

            Action::make('return')
                ->hidden($check == 0)
                ->color('danger')
                ->label('Arsip Sedang Dipinjam')
                ->icon('heroicon-o-document-arrow-up')
                ->disabled()

        ];
    }
}
