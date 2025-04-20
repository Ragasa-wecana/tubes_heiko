<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Form untuk create & edit data.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_barang')
                    ->default(fn () => Barang::getKodeBarang())
                    ->label('Kode Barang')
                    ->required()
                    ->readonly(),

                TextInput::make('nama_barang')
                    ->label('Nama Barang')
                    ->required()
                    ->placeholder('Masukkan nama barang'),

                select::make('kategori_barang')
                    ->label('Kategori Barang')
                    ->options([
                        'Kemeja' => 'Kemeja',
                        'Jaket' => 'Jaket',
                        'Kaos' => 'Kaos',
                    ])
                    ->required(),

                select::make('ukuran')
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
                    ->minValue(0)
                    ->reactive()
                    ->placeholder('Masukkan harga barang')
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('harga_barang', number_format((int) str_replace('.', '', $state), 0, ',', '.'))
            ),

                FileUpload::make('foto')
                    ->label('Foto')
                    ->directory('foto')
                    ->required(),

                TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->placeholder('Masukkan jumlah stock')
                    ->minValue(0)
                    ->numeric(),
                Select::make('kode_supplier')
                    ->label('Supplier')
                    ->relationship('supplier', 'nama') // 'nama' = kolom nama supplier
                    ->searchable()
                    ->required(),
                
            ]);
    }

    /**
     * Tabel untuk menampilkan daftar data.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                    ->extraAttributes(['class' => 'text-right'])
                    ->sortable(),

                ImageColumn::make('foto')
                    ->label('Foto'),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable(),
                TextColumn::make('supplier.nama')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable(),                
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
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

    /**
     * Relasi (jika ada) didefinisikan di sini.
     */
    public static function getRelations(): array
    {
        return [
            // RelationManagers\SomethingRelationManager::class,
        ];
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
