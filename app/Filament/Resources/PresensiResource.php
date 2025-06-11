<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PresensiResource\Pages;
use App\Filament\Resources\PresensiResource\RelationManagers;
use App\Models\Presensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;

// tambahan untuk tombol unduh pdf
    use Filament\Tables\Actions\Action;
    use Barryvdh\DomPDF\Facade\Pdf; // Kalau kamu pakai DomPDF
    use Illuminate\Support\Facades\Storage;

class PresensiResource extends Resource
{
    protected static ?string $model = Presensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Masterdata';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_karyawan')
                    ->options([
                    'K-001' => 'K-001',
                    'K-002' => 'K-002',
                    'K-003' => 'K-003',
                    'K-004' => 'K-004',
                    'K-005' => 'K-005',
                ])
                ->default('id_karyawan'),
            
                Select::make('nama_karyawan')
                    ->options([
                    'Nuni' => 'Nuni',
                    'Ratih Anggraini' => 'Ratih Anggraini',
                    'Wulansari' => 'Wulansari',
                    'Salsabila Putri' => 'Salsabila Putri',
                ])
                ->default('nama_karyawan'),

                DatePicker::make('tanggal')
                    ->label('tanggal')
                    ->required(),
                
                Select::make('status')
                    ->options([
                    'Hadir' => 'Hadir',
                    'Izin' => 'Izin',
                    'Sakit' => 'Sakit',
                    ])
                ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_karyawan')->searchable(),
                Tables\Columns\TextColumn::make('nama_karyawan')->searchable(),
                Tables\Columns\TextColumn::make('tanggal')->searchable(),
                BadgeColumn::make('status')
                    ->color(fn ($state) => match ($state) {
                        'Hadir' => 'success',
                        'Izin' => 'warning',
                        'Sakit' => 'Danger',
                        default => 'success',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            // tombol tambahan
            ->headerActions([
                // tombol tambahan export pdf
                // ✅ Tombol Unduh PDF
                Action::make('downloadPdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $presensi = Presensi::all();

                    $pdf = Pdf::loadView('pdf.presensi', ['presensi' => $presensi]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'presensi-list.pdf'
                    );
                })
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
            'index' => Pages\ListPresensis::route('/'),
            'create' => Pages\CreatePresensi::route('/create'),
            'edit' => Pages\EditPresensi::route('/{record}/edit'),
        ];
    }
}