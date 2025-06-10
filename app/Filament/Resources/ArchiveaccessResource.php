<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArchiveaccessResource\Pages;
use App\Filament\Resources\ArchiveaccessResource\RelationManagers;
use App\Models\Archiveaccess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArchiveaccessResource extends Resource
{
    protected static ?string $model = Archiveaccess::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationGroup = 'Pengkategorian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('archive_access')
                    ->required()
                    ->label('SKKAD')
                    ->maxLength(255),
                Forms\Components\Select::make('access_limitation')
                    ->required()
                    ->label('Keterbatasan Akses')
                    ->options([
                        '0' => 'Terbatas',
                        '1' => 'Umum',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('archive_access')
                    ->label('SKKAD')
                    ->searchable(),
                Tables\Columns\TextColumn::make('access_limitation')
                    ->label('Keterbatasan Akses')
                    ->formatStateUsing(function ($record) {

                        return $record->access_limitation == 1 ? 'Umum' : 'Terbatas';

                    })
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageArchiveaccesses::route('/'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'id') {
            return "SKKAD";
        }
        else
        {
            return "SKKAD";
        }
    }


}
