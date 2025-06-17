<?php

namespace App\Filament\Widgets;

use App\Models\Archive;
use App\Models\Archivelending;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ArchiveOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $baseQuery = fn ($query) => $query->when($startDate, fn (Builder $query) => $query->whereDate('date_make', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('date_make', '<=', $endDate));

        $totalArchives = Archive::query()->when($startDate || $endDate, $baseQuery)->count();
        //Archive Lending
        $archivelending = Archivelending::query()->when($startDate || $endDate, $baseQuery);
        $totalArchivelendings = $archivelending->count();
        $totalLateReturn = $archivelending->where('lending_approval', 1)->where('lending_until', '<', now())->whereNull('return_date')->count();
        $totalArchivenotreturned = $archivelending->where('lending_approval', 1)->whereNull('return_date')->count();

        return [
            Stat::make('Total Arsip', $totalArchives),
            Stat::make('Total Peminjaman', $totalArchivelendings),
            Stat::make('Total Arsip Sedang Dipinjam', $totalArchivenotreturned),
            Stat::make('Total Arsip Telat Dikembalikan', $totalLateReturn),
        ];
    }
}
