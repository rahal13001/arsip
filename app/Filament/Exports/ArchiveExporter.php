<?php

namespace App\Filament\Exports;

use App\Models\Archive;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Style;

class ArchiveExporter extends Exporter
{
    protected static ?string $model = Archive::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name')
                ->label('Pembuat'),
            ExportColumn::make('filecode.file_code')
                ->label('Kode File'),
            ExportColumn::make('filecode.code_name')
                ->label('Nama Kode'),
            ExportColumn::make('archivetype.archive_type')
                ->label('Jenis Arsip'),
            ExportColumn::make('accessclassification.access_classification')
                ->label('Klasifikasi'),
            ExportColumn::make('archive_name')
                ->label('Nama Arsip'),
            ExportColumn::make('document_number')
                ->label('Nomor Dokumen'),
            ExportColumn::make('archive_number')
                ->label('Nomor Arsip'),
            ExportColumn::make('date_make')
                ->label('Tanggal Pembuatan'),
            ExportColumn::make('date_input')
                ->label('Tanggal Input'),
            ExportColumn::make('archive_description')
                ->label('Deskripsi'),
            ExportColumn::make('sheet_number')
                ->label('Jumlah Lembaran'),
            ExportColumn::make('storage_location')
                ->label('Lokasi Penyimpanan'),
            ExportColumn::make('development')
                ->label('Tingkat Pengembangan'),
            ExportColumn::make('document')
                ->formatStateUsing(fn(Archive $record) => 'https://sipanda.timurbersinar.com/storage/'.$record->document)
                ->label('Link Dokumen'),
            ExportColumn::make('archiveaccess.archive_access')
                ->label('Akses Arsip'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your archive export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getXlsxCellStyle(): ?Style
    {
        return (new Style())
            ->setFontSize(12)
            ->setFontName('Arial')
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER)
            ->setShouldWrapText(true)
            ->setFormat('d-m-yy h:mm');
    }

    public function getXlsxHeaderCellStyle(): ?Style
    {
        return (new Style())
            ->setFontBold()
            ->setFontSize(12)
            ->setFontName('Arial')
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER);
    }
}
