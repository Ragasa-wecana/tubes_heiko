<?php

namespace App\Filament\Resources;

use App\Filament\Exports\BarangExporter;
use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\Action as TableAction; // alias agar jelas
use Barryvdh\DomPDF\Facade\Pdf; // Kalau kamu pakai DomPDF
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\UserExporter;
use Illuminate\Support\Facades\Storage;
class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Masterdata';

    /**
     * Form untuk create & edit data.
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('kode_barang')
                ->default(fn() => Barang::generateKodeBarang()) // Pastikan metode generateKodeBarang() ada di model Barang
                ->label('Kode Barang')
                ->required()
                ->readonly(),

            TextInput::make('nama_barang')
                ->label('Nama Barang')
                ->required()
                ->placeholder('Masukkan nama barang'),

            Select::make('kategori_barang')
                ->label('Kategori Barang')
                ->options([
                    'Kemeja' => 'Kemeja',
                    'Jaket' => 'Jaket',
                    'Kaos' => 'Kaos',
                ])
                ->required(),

            Select::make('ukuran')
                ->label('Ukuran Barang')
                ->options([
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                    'XXL' => 'XXL',
                ])
                ->required(),

            TextInput::make('harga_barang')
                ->label('Harga Barang')
                ->required()
                ->numeric()
                ->minValue(0)
                ->placeholder('Masukkan harga barang'),

            FileUpload::make('foto')
                ->label('Foto')
                ->directory('foto')
                ->required(),

            TextInput::make('stock')
                ->label('Stock')
                ->required()
                ->numeric()
                ->placeholder('Masukkan jumlah stock'),

            Select::make('supplier_id')
                ->label('Supplier')
                ->relationship('supplier', 'nama_supplier') // Relasi: barang.supplier_id dengan supplier.nama_supplier
                ->searchable()
                ->required(),
        ]);
    }

    /**
     * Tabel untuk menampilkan daftar data barang.
     */
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('kode_barang')
                ->label('Kode Barang')
                ->searchable(),

            TextColumn::make('nama_barang')
                ->label('Nama Barang')
                ->searchable()
                ->sortable(),

            TextColumn::make('kategori_barang')
                ->label('Kategori Barang')
                ->searchable()
                ->sortable(),

            TextColumn::make('ukuran')
                ->label('Ukuran')
                ->searchable()
                ->sortable(),

            TextColumn::make('harga_barang')
                ->label('Harga Barang')
                ->sortable(),

            ImageColumn::make('foto')
                ->label('Foto'),

            TextColumn::make('stock')
                ->label('Stock')
                ->sortable(),

            TextColumn::make('supplier.nama_supplier')
                ->label('Supplier')
                ->sortable()
                ->searchable(),
        ])
            ->filters([
                // Tambahkan filter bila diperlukan
            ])
            ->headerActions([
                ExportAction::make()->exporter(BarangExporter::class)->color('success'),
                // tombol tambahan export pdf
                // ✅ Tombol Unduh PDF
                TableAction::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $barang = Barang::all();

                        $pdf = Pdf::loadView('pdf.barang', ['barang' => $barang]);

                        return response()->streamDownload(
                            fn() => print ($pdf->output()),
                            'barang-list.pdf'
                        );
                    })
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    /**
     * Rute untuk halaman CRUD (List, Create, Edit).
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
