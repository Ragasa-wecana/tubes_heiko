<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BukuBesarResource\Pages;
use App\Models\BukuBesar;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BukuBesarResource extends Resource
{
    protected static ?string $model = BukuBesar::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        // Kosongkan karena form tidak digunakan
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal'),
                Tables\Columns\TextColumn::make('keterangan')->label('Keterangan'),
                Tables\Columns\TextColumn::make('debit')->label('Debit'),
                Tables\Columns\TextColumn::make('kredit')->label('Kredit'),
                Tables\Columns\TextColumn::make('saldo')->label('Saldo'),
            ])
            ->filters([]) // Tidak ada filter
            ->actions([]) // Tidak ada edit/delete
            ->bulkActions([]); // Tidak ada aksi massal
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBukuBesars::route('/'),
        ];
    }
}
