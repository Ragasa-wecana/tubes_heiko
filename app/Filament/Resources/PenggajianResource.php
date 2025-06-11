<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianResource\Pages;
use App\Filament\Resources\PenggajianResource\RelationManagers;
use App\Models\Penggajian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Presensi;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;

class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Select::make('nama_karyawan')
                    ->label('Nama Karyawan')
                    ->options(Presensi::pluck('nama_karyawan')->toArray()) // Mengambil data dari tabel
                    ->required()
                    ->placeholder('Pilih Nama'),

            
                TextInput::make('jabatan')
                    ->label('Jabatan Karyawan')
                    ->autocapitalize('words')
                    ->required()
                    ->placeholder('Masukkan jabatan karyawan'),
                
                TextInput::make('gaji_pokok')
                    ->label('Gaji Pokok')
                    ->autocapitalize('words')
                    ->required()
                    ->placeholder('Masukkan gaji pokok'),
                
                TextInput::make('potongan_gaji')
                    ->label('Potongan Gaji')
                    ->autocapitalize('words')
                    ->required()
                    ->placeholder('Masukkan potongan gaji'),
                
                TextInput::make('total_gaji')
                    ->label('Total Gaji')
                    ->autocapitalize('words')
                    ->required()
                    ->placeholder('Masukkan total gaji'),
                
                DatePicker::make('tanggal_pembayaran')
                    ->label('tanggal')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_karyawan')->searchable(),
                Tables\Columns\TextColumn::make('nama_karyawan')->searchable(),
                Tables\Columns\TextColumn::make('jabatan')->searchable(),
                Tables\Columns\TextColumn::make('gaji_pokok')->searchable(),
                Tables\Columns\TextColumn::make('potongan_gaji')->searchable(),
                Tables\Columns\TextColumn::make('total_gaji')->searchable(),
                Tables\Columns\TextColumn::make('gaji_pokok')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pembayaran')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenggajians::route('/'),
            'create' => Pages\CreatePenggajian::route('/create'),
            'edit' => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }
}