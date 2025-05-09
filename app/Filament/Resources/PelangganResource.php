<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Filament\Resources\PelangganResource\RelationManagers;
use App\Models\Pelanggan;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn; // Corrected import
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1) // Membuat hanya 1 kolom
                ->schema([
                    TextInput::make('kode_pelanggan')
                        ->required()
                        ->placeholder('Masukkan kode pelanggan'),
                    TextInput::make('nama_pelanggan')
                        ->required()
                        ->placeholder('Masukkan nama pelanggan'),
                    Radio::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan' => 'Perempuan',
                        ])
                        ->required(),
                    TextInput::make('no_telp')
                        ->required()
                        ->placeholder('Masukkan nomor telepon'),
                    TextInput::make('alamat')
                        ->required()
                        ->placeholder('Masukkan alamat'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pelanggan')
                    ->searchable(),
                TextColumn::make('nama_pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenis_kelamin')
                    ->label('jenis_kelamin'),
                TextColumn::make('no_telp'),
                TextColumn::make('alamat'),
            ])
            ->filters([
               
                    ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relations go here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}
