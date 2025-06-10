<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianResource\Pages;
use App\Models\Penggajian;
use App\Models\Karyawan;
use App\Models\Presensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;  // Correct import
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_karyawan')
                    ->label('Nama Karyawan')
                    ->options(Karyawan::pluck('nama_karyawan', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        $karyawan = Karyawan::find($state);
                        if ($karyawan) {
                            $set('jabatan', $karyawan->jabatan);
                        }
                    }),

                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->disabled()
                    ->dehydrated(), // atau hapus saja karena default-nya true


                TextInput::make('gaji_pokok')
                    ->label('Gaji Pokok')
                    ->numeric()
                    ->required(),

                TextInput::make('potongan_gaji')
                    ->label('Potongan')
                    ->numeric()
                    ->required(),

                DatePicker::make('tanggal_pembayaran')
                    ->label('Tanggal Pembayaran')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('karyawan.nama')->label('Nama'),
                TextColumn::make('jabatan'),
                TextColumn::make('gaji_pokok')->money('IDR'),
                TextColumn::make('potongan')->money('IDR'),
                TextColumn::make('total_gaji')->label('Total Gaji')->money('IDR'),
                TextColumn::make('bulan'),
                TextColumn::make('tahun'),
                TextColumn::make('tanggal_pembayaran')->date(),
            ])
            ->actions([
                EditAction::make(),  // Correct usage
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
