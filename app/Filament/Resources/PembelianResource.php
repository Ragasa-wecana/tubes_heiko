<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Filament\Resources\PembelianResource\RelationManagers;
use App\Models\Pembelian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard; //untuk menggunakan wizard
use Filament\Forms\Components\TextInput; //untuk penggunaan text input
use Filament\Forms\Components\DateTimePicker; //untuk penggunaan date time picker
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select; //untuk penggunaan select
use Filament\Forms\Components\Repeater; //untuk penggunaan repeater
use Filament\Tables\Columns\TextColumn; //untuk tampilan tabel
use Filament\Forms\Components\Placeholder; //untuk menggunakan text holder
use Filament\Forms\Get; //menggunakan get 
use Filament\Forms\Set; //menggunakan set 
use Filament\Forms\Components\Hidden; //menggunakan hidden field
use Filament\Tables\Filters\SelectFilter; //untuk menambahkan filter
use App\Models\ListSupplier;
use App\Models\Barang;
use App\Models\PembayaranBarang;
use App\Models\PembelianBarang;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action as TableAction; // alias agar jelas
use Barryvdh\DomPDF\Facade\Pdf; // Kalau kamu pakai DomPDF
use Illuminate\Support\Facades\Storage;
// untuk dapat menggunakan action
use Filament\Forms\Components\Actions\Action;
class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Pembelian';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Wizard
                Wizard::make([
                    Wizard\Step::make('Pesanan')
                        ->schema([
                            // section 1
                            Forms\Components\Section::make('Faktur') // Bagian pertama
                                // ->description('Detail Barang')
                                ->icon('heroicon-m-document-duplicate')
                                ->schema([
                                    TextInput::make('no_faktur')
                                        ->default(fn() => Pembelian::getKodeFaktur()) // Ambil default dari method getKodeBarang
                                        ->label('Nomor Faktur')
                                        ->required()
                                        ->readonly() // Membuat field menjadi read-only
                                    ,
                                    DateTimePicker::make('tgl')->default(now()) // Nilai default: waktu sekarang
                                    ,
                                    Select::make('list_supplier_id')
                                        ->label('Supplier')
                                        ->options(ListSupplier::pluck('nama_supplier', 'id')->toArray()) // Mengambil data dari tabel
                                        ->required()
                                        ->placeholder('Pilih Supplier') // Placeholder default
                                    ,
                                    TextInput::make('tagihan')
                                        ->default(0) // Nilai default
                                        ->hidden()
                                    ,
                                    TextInput::make('status')
                                        ->default('pesan') // Nilai default status pemesanan adalah pesan/bayar/kirim
                                        ->hidden()
                                    ,
                                ])
                                ->collapsible() // Membuat section dapat di-collapse
                                ->columns(3)
                            ,
                        ]),
                    Wizard\Step::make('Pilih Barang')
                        ->schema([
                            Repeater::make('items')
                                ->relationship('pembelianbarang')
                                ->schema([
                                    Select::make('barang_id')
                                        ->label('Barang')
                                        ->options(Barang::pluck('nama_barang', 'id')->toArray())
                                        ->required()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->reactive()
                                        ->placeholder('Pilih Barang')
                                        ->afterStateUpdated(function ($state, $set) {
                                            $barang = Barang::find($state);
                                            $set('harga_beli', $barang ? $barang->harga_barang : 0);
                                        })
                                        ->searchable(),

                                    TextInput::make('harga_beli')
                                        ->label('Harga Beli')
                                        ->numeric()
                                        ->reactive()
                                        ->default(fn($get) => $get('barang_id') ? Barang::find($get('barang_id'))?->harga_barang ?? 0 : 0)
                                        ->required()
                                        ->dehydrated(),

                                    TextInput::make('jml')
                                        ->label('Jumlah')
                                        ->default(1)
                                        ->numeric()
                                        ->reactive()
                                        ->live()
                                        ->required(),

                                    DatePicker::make('tgl')
                                        ->default(today())
                                        ->required(),
                                ])
                                ->columns([
                                    'md' => 4,
                                ])
                                ->addable()
                                ->deletable()
                                ->reorderable()
                                ->createItemButtonLabel('Tambah Item')
                                ->minItems(1)
                                ->required()
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $totalTagihan = collect($get('items'))
                                        ->sum(fn($item) => (float) ($item['harga_beli'] ?? 0) * (int) ($item['jml'] ?? 0));
                                    $set('tagihan', $totalTagihan);
                                }),

                            Forms\Components\Actions::make([
                                Forms\Components\Actions\Action::make('Simpan Sementara')
                                    ->action(function ($get) {
                                        $pembelian = Pembelian::updateOrCreate(
                                            ['no_faktur' => $get('no_faktur')],
                                            [
                                                'tgl' => $get('tgl'),
                                                'list_supplier_id' => $get('list_supplier_id'),
                                                'status' => 'pesan',
                                                'tagihan' => 0
                                            ]
                                        );

                                        foreach ($get('items') as $item) {
                                            PembelianBarang::updateOrCreate(
                                                [
                                                    'pembelian_id' => $pembelian->id,
                                                    'barang_id' => $item['barang_id']
                                                ],
                                                [
                                                    'harga_beli' => $item['harga_beli'],
                                                    'jml' => $item['jml'],
                                                    'tgl' => $item['tgl'],
                                                ]
                                            );

                                            $barang = Barang::find($item['barang_id']);
                                            if ($barang) {
                                                $barang->increment('stock', $item['jml']);
                                            }
                                        }

                                        $totalTagihan = PembelianBarang::where('pembelian_id', $pembelian->id)
                                            ->sum(DB::raw('harga_beli * jml'));

                                        $pembelian->update(['tagihan' => $totalTagihan]);
                                    })
                                    ->label('Proses')
                                    ->color('primary'),

                            ])

                            // 
                        ])
                    ,
                    Wizard\Step::make('Pembayaran')
                        ->schema([
                            Placeholder::make('Tabel Pembayaran')
                                ->content(fn(Get $get) => view('filament.components.pembelian-table', [
                                    'pembayarans' => Pembelian::where('no_faktur', $get('no_faktur'))->get()
                                ])),
                        ]),
                ])->columnSpan(3)
                // Akhir Wizard
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_faktur')->label('No Faktur')->searchable(),
                TextColumn::make('list_supplier.nama_supplier') // Relasi ke nama pembeli
                    ->label('Nama Supplier')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bayar' => 'success',
                        'pesan' => 'warning',
                    }),
                TextColumn::make('tagihan')
                    ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                    // ->extraAttributes(['class' => 'text-right']) // Tambahkan kelas CSS untuk rata kanan
                    ->sortable()
                    ->alignment('end') // Rata kanan
                ,
                TextColumn::make('created_at')->label('Tanggal')->dateTime(),

            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pesan' => 'Pemesanan',
                        'bayar' => 'Pembayaran',
                    ])
                    ->searchable()
                    ->preload(), // Menampilkan semua opsi saat filter diklik
                    ])  
                     // tombol tambahan
            ->headerActions([
                // tombol tambahan export pdf
                // ✅ Tombol Unduh PDF
                TableAction::make('downloadPdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $pembelian = pembelian::all();

                    $pdf = Pdf::loadView('pdf.pembelian', ['pembelian' => $pembelian]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'pembelian-list.pdf'
                    );
                })
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}