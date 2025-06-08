<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArchiveResource\Pages;
use App\Filament\Resources\ArchiveResource\RelationManagers;
use App\Models\Archive;
use App\Models\Filecode;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section as FormsSection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArchiveResource extends Resource
{
    protected static ?string $model = Archive::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make()
                        ->schema([
                            Forms\Components\Select::make('user_id')
                                ->label('Pembuat')
                                ->relationship(
                                    name : 'user',
                                    titleAttribute: 'name',
                                    modifyQueryUsing:fn($query)=>$query->where('status', 1)
                                )
                                ->preload()
                                ->searchable()
                                ->required(),
                            Forms\Components\Select::make('filecode_id')
                                ->searchable()
                                ->label('Kode File')
                                ->relationship('filecode')
                                ->getOptionLabelFromRecordUsing(fn (Filecode $record) => "{$record->file_code} ({$record->code_name})")
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('archivetype_id')
                                ->relationship('archivetype', 'archive_type')
                                ->searchable()
                                ->label('Jenis Arsip')
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('accessclassification_id')
                                ->relationship('accessclassification', 'access_classification')
                                ->searchable()
                                ->label('Klasifikasi Arsip')
                                ->preload()
                                ->required(),
                        ])
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make()
                        ->schema([
                            Forms\Components\TextInput::make('archive_name')
                                ->label('Nama Arsip')
                                ->required()
                                ->columnSpanFull()
                                ->maxLength(255),
                            Forms\Components\DatePicker::make('date_make')
                                ->label('Tanggal Pembuatan'),
                            Forms\Components\DatePicker::make('date_input')
                                ->label('Tanggal Input')
                                ->required(),
                            Forms\Components\Textarea::make('archive_description')
                                ->label('Deskripsi Arsip')
                                ->required()
                                ->columnSpanFull(),
                        ])
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make()
                        ->schema([
                            Forms\Components\TextInput::make('sheet_number')
                                ->label('Jumlah Lembaran')
                                ->required()
                                ->numeric(),
                            Forms\Components\Select::make('development')
                                ->label('Tingkat Pengembangan')
                                ->required()
                                ->options([
                                    'Asli' => 'Asli',
                                    'Fotocopy' => 'Fotokopi',
                                ]),
                            Forms\Components\TextInput::make('storage_location')
                                ->label('Lokasi Penyimpanan')
                                ->required()
                                ->columnSpanFull()
                                ->maxLength(255),

                            Forms\Components\FileUpload::make('document')
                                ->columnSpanFull()
                                ->required()
                                ->label('File Arsip')
                                ->disk('public')
                                ->directory('arsip')
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/doc',
                                    'application/docx',
                                    'application/xls',
                                    'application/xlsx',
                                    'application/ppt',
                                    'application/pptx',
                                    'application/zip',
                                    'application/rar',
                                    'application/7z',
                                    'application/txt',
                                    'application/jpg',
                                    'application/jpeg',
                                    'application/png',
                                    'application/gif',
                                    'application/bmp',
                                    'application/tif',
                                    'application/tiff',
                                    'application/mp3',
                                    'application/mp4',
                                    'application/mpeg',
                                    'application/avi',
                                    'application/wmv',
                                    'application/mov',
                                    'application/3gp',
                                    'application/3g2',
                                    'application/3gpp',
                                    'application/3gpp2',
                                    'application/mkv',
                                    'application/m4a',
                                    'application/m4v',
                                ])
                            ->maxSize(51240),
                        ])
                    ]),

                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Fieldset::make()
                        ->relationship('archiveuser')
                        ->schema([
                            Forms\Components\DatePicker::make('active_date')
                                ->required()
                                ->label('Tanggal Aktif'),
                            Forms\Components\DatePicker::make('inactive_date')
                                ->label('Tanggal Inaktif'),
                            Forms\Components\DatePicker::make('destruction_date')
                                ->label('Tanggal Usul Musnah'),

                            Forms\Components\TextInput::make('active_save_time')
                                ->label('Masa Simpan Aktif')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('inactive_save_time')
                                ->label('Masa Simpan Inaktif')
                                ->numeric(),
                            Forms\Components\Select::make('archive_properties')
                                ->label('Sifat Arsip')
                                ->required()
                                ->options([
                                    'Aktif' => 'Aktif',
                                    'Inaktif' => 'Inaktif',
                                    'Vital' => 'Vital',
                                ]),
                            Forms\Components\Select::make('archive_status')
                                ->label('Status Arsip')
                                ->required()
                                ->options([
                                    1 => 'Permanen',
                                    2 => 'Musnah',
                                ]),

                        ])
                        ->columns(3)
                ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make()
                            ->relationship('archiveuser')
                            ->schema([
                                Forms\Components\Textarea::make('note')
                                    ->label('Catatan')
                                    ->columnSpanFull(),
                            ])
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 75])
            ->extremePaginationLinks()
            ->striped()
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('archive_number')
                    ->label('Nomor Arsip')
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        // Get archive_number from your model (e.g. '12')
                        $archiveNumber = $record->archive_number;
                        // Get related file_code (make sure filecode relationship is loaded)
                        $fileCode = optional($record->filecode)->file_code;
                        // In case date_input is a string, use:
                        $year = \Illuminate\Support\Carbon::parse($record->date_input)->format('Y');
                        // Build suffix, fallback to empty string if file_code or date_input missing
                        return $archiveNumber . '/' . ($fileCode ?: '-') . '/' . ($year ?: '-');
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        // This allows searching for individual parts, like "1", "MK", or "2025"
                        // as well as combined parts like "1/MK"
                        if (str_contains($search, '/')) {
                            $searchParts = explode('/', $search);

                            // Apply a query for each part of the search string
                            return $query->where(function (Builder $q) use ($searchParts) {
                                if (isset($searchParts[0]) && !empty($searchParts[0])) {
                                    $q->where('archive_number', 'like', '%' . $searchParts[0] . '%');
                                }
                                if (isset($searchParts[1]) && !empty($searchParts[1])) {
                                    $q->whereHas('filecode', function (Builder $subQ) use ($searchParts) {
                                        $subQ->where('file_code', 'like', '%' . $searchParts[1] . '%');
                                    });
                                }
                                if (isset($searchParts[2]) && !empty($searchParts[2])) {
                                    $q->whereYear('date_input', $searchParts[2]);
                                }
                            });
                        }

                        // Fallback: If no "/", search across multiple individual fields.
                        // This makes it possible to search for "MK" or "2025" directly.
                        return $query->where(function (Builder $q) use ($search) {
                            $q->where('archive_number', 'like', "%{$search}%")
                                ->orWhereHas('filecode', function (Builder $subQ) use ($search) {
                                    $subQ->where('file_code', 'like', "%{$search}%");
                                })
                                ->orWhereRaw("YEAR(date_input) like ?", ["%{$search}%"]);
                        });
                    }),
                Tables\Columns\TextColumn::make('archive_name')
                    ->label('Nama Arsip')
                    ->sortable()
                    ->searchable()
                    ->limit(75)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    })
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('archiveuser.archive_status')
                    ->label('Status Arsip')
                    ->sortable(),

                Tables\Columns\TextColumn::make('archivetype.archive_type')
                    ->label('Jenis Arsip')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pembuat')
                    ->sortable(),
                Tables\Columns\TextColumn::make('accessclassification.access_classification')
                    ->label('Klasifikasi Arsip')
                    ->sortable(),
                Tables\Columns\TextColumn::make('archiveuser.archive_properties')
                    ->label('Sifat Arsip')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_make')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('filecode.file_code')
                    ->label('Kode File')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_input')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('storage_location')
                    ->label('Lokasi Penyimpanan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('development')
                    ->label('Tingkat Pengembangan')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

            ])
            ->filters([
                SelectFilter::make('filecode_id')
                    ->label('Kode File')
                    ->multiple()
                    ->preload()
                    ->relationship('filecode', 'file_code')
                    ->getOptionLabelFromRecordUsing(fn (Filecode $record) => "{$record->file_code} ({$record->code_name})"),
                SelectFilter::make('archivetype_id')
                    ->label('Jenis Arsip')
                    ->multiple()
                    ->preload()
                    ->relationship('archivetype', 'archive_type'),
                SelectFilter::make('accessclassification_id')
                    ->label('Klasifikasi Arsip')
                    ->multiple()
                    ->preload()
                    ->relationship('accessclassification', 'access_classification'),
                Filter::make('archiveuser.archive_properties')
                    ->label('Sifat Arsip')
                    ->form([
                        Forms\Components\Select::make('archive_properties')
                            ->label('Sifat Arsip')
                            ->multiple()
                            ->preload()
                            ->options([
                                'Aktif' => 'Aktif',
                                'Inaktif' => 'Inaktif',
                                'Vital' => 'Vital',
                            ])
                    ])
                    //display filter indicator
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['archive_properties']) {
                            return null;
                        }

                        return 'Sifat Arsip ' . implode(', ', $data['archive_properties']);
                    })
                    //apply filter to query
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['archive_properties'],
                                fn (Builder $query, $status): Builder => $query->whereHas('archiveuser', fn ($query) => $query->whereIn('archive_properties', $status)),
                            );
                    }),

                Filter::make('archiveuser.archive_status')
                    ->label('Status Arsip')
                    ->form([
                        Forms\Components\Select::make('archive_status')
                            ->label('Status Arsip')
                            ->multiple()
                            ->preload()
                            ->options([
                                1 => 'Permanen',
                                0 => 'Musnah',
                            ])
                    ])
                    //display filter indicator
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['archive_status']) {
                            return null;
                        }

                        return 'Status Arsip ' . implode(', ', $data['archive_status']);
                    })
                    //apply filter to query
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['archive_status'],
                                fn (Builder $query, $status): Builder => $query->whereHas('archiveuser', fn ($query) => $query->whereIn('archive_status', $status)),
                            );
                    }),

                Filter::make('date_make')
                    ->label('Tanggal Pembuatan')
                    ->form([
                        DatePicker::make('date_make_from')
                            ->label('Tanggal Buat Dari'),
                        DatePicker::make('date_make_until')
                            ->label('Tanggal Buat Sampai'),
                    ])
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['date_make_from'] ?? null) {
                            $indicators[] = Indicator::make('Tanggal Buat Dari ' . Carbon::parse($data['date_make_from'])->toFormattedDateString())
                                ->removeField('from');
                        }

                        if ($data['date_make_until'] ?? null) {
                            $indicators[] = Indicator::make('Tanggal Buat Sampai ' . Carbon::parse($data['date_make_until'])->toFormattedDateString())
                                ->removeField('until');
                        }

                        return $indicators;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_make_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_make', '>=', $date),
                            )
                            ->when(
                                $data['date_make_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_make', '<=', $date),
                            );
                    }),
                Filter::make('date_input')
                    ->label('Tanggal Input')
                    ->form([
                        DatePicker::make('date_input_from')
                            ->label('Tanggal Input Dari'),
                        DatePicker::make('date_input_until')
                            ->label('Tanggal Input Sampai'),
                    ])
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['date_input_from'] ?? null) {
                            $indicators[] = Indicator::make('Tanggal Input Dari ' . Carbon::parse($data['date_input_from'])->toFormattedDateString())
                                ->removeField('from');
                        }

                        if ($data['date_input_until'] ?? null) {
                            $indicators[] = Indicator::make('Tanggal Input Sampai ' . Carbon::parse($data['date_input_until'])->toFormattedDateString())
                                ->removeField('until');
                        }

                        return $indicators;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_input_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_input', '>=', $date),
                            )
                            ->when(
                                $data['date_input_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_input', '<=', $date),
                            );
                    }),
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->filtersFormSchema(fn (array $filters): array => [
                FormsSection::make('Data Arsip')
                    ->description('Filter akan menampilkan data berdasarkan kriteria yang dipilih.')
                    ->schema([
                        $filters['filecode_id'],
                        $filters['archivetype_id'],
                        $filters['accessclassification_id'],
                        $filters['archiveuser.archive_properties'],
                        $filters['archiveuser.archive_status'],
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->columnSpanFull(),
                FormsSection::make('Filter Tanggal')
                    ->description('Filter akan menampilkan data berdasarkan tanggal yang dipilih.')
                    ->schema([
                        $filters['date_make'],
                        $filters['date_input'],
                    ])->columns(2)
                    ->collapsible()
                    ->columnSpanFull(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->tooltip('Aksi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                    Section::make('Informasi Arsip')
                        ->schema([
                            TextEntry::make('archive_name')
                                ->label('Judul Arsip')
                                ->weight(FontWeight::Bold),
                            TextEntry::make('archive_number')
                                ->weight(FontWeight::Bold)
                                ->formatStateUsing(function ($record) {
                                    // Get archive_number from your model (e.g. '12')
                                    $archiveNumber = $record->archive_number;
                                    // Get related file_code (make sure filecode relationship is loaded)
                                    $fileCode = optional($record->filecode)->file_code;
                                    // In case date_input is a string, use:
                                    $year = \Illuminate\Support\Carbon::parse($record->date_input)->format('Y');
                                    // Build suffix, fallback to empty string if file_code or date_input missing
                                    return $archiveNumber . '/' . ($fileCode ?: '-') . '/' . ($year ?: '-');
                                })
                                ->label('Nomor Arsip'),
                        ])
                        ->collapsible(),
                    Section::make('Informasi Umum')
                        ->schema([
                            TextEntry::make('user.name')
                                ->label('Pembuat')
                                ->weight(FontWeight::Bold),
                            TextEntry::make('archivetype.archive_type')
                                ->weight(FontWeight::Bold)
                                ->label('Jenis Arsip'),
                            TextEntry::make('accessclassification.access_classification')
                                ->weight(FontWeight::Bold)
                                ->label('Klasifikasi Arsip'),
                            TextEntry::make('filecode.file_code')
                                ->label('Kode File')
                                ->weight(FontWeight::Bold)
                                ->formatStateUsing(function ($record) {
                                   // Get related file_code (make sure filecode relationship is loaded)
                                    $fileCode = optional($record->filecode)->file_code;
                                    $codeName = optional($record->filecode)->code_name;
                                    // In case date_input is a string, use:
                                    // Build suffix, fallback to empty string if file_code or date_input missing
                                    return ($fileCode ?: '-') . ' (' . ($codeName ?: '-') . ')';
                                }),
                            TextEntry::make('archiveuser.archive_properties')
                                ->label('Sifat Arsip')
                                ->weight(FontWeight::Bold),
                            TextEntry::make('archiveuser.archive_status')
                                ->label('Status Arsip')
                                ->weight(FontWeight::Bold),

                        ])->columns(2)
                        ->collapsible(),

                 Section::make('Informasi Detail')
                    ->schema([
                        TextEntry::make('date_make')
                            ->label('Tanggal Pembuatan')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('date_input')
                            ->label('Tanggal Input')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('active_date')
                            ->label('Tanggal Aktif')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('inactive_date')
                            ->label('Tanggal Inaktif')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('destruction_date')
                            ->label('Tanggal Usul Musnah')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('active_save_time')
                            ->label('Masa Simpan Aktif')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('inactive_save_time')
                            ->label('Masa Simpan Inaktif')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('storage_location')
                            ->label('Lokasi Penyimpanan')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('development')
                            ->label('Tingkat Pengembangan')
                            ->weight(FontWeight::Bold),

                    ])
                     ->columns(2)
                     ->collapsible(),
                Section::make('Catatan')
                    ->schema([
                        TextEntry::make('archive_description')
                            ->label('Deskripsi Arsip')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('archiveuser.note')
                            ->label('Catatan')
                            ->weight(FontWeight::Bold),
                        IconEntry::make('document')
                            ->label('Dokumen Arsip')
                            ->size(IconEntry\IconEntrySize::Large)
                            ->icon('heroicon-o-eye')
                            ->color('info')
                            ->url(fn ($record) => $record->document ? Storage::url($record->document) : null, true)
                        ])
                ->collapsible(),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArchives::route('/'),
            'create' => Pages\CreateArchive::route('/create'),
            'view' => Pages\ViewArchive::route('/{record}'),
            'edit' => Pages\EditArchive::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'id') {
            return "Arsip";
        }
        else
        {
            return "Archive";
        }
    }


}
