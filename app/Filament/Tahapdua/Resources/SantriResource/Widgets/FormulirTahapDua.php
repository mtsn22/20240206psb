<?php

namespace App\Filament\Tahapdua\Resources\SantriResource\Widgets;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kodepos;
use App\Models\Pendaftar;
use App\Models\Provinsi;
use App\Models\Qism;
use App\Models\QismDetail;
use App\Models\QismDetailHasKelas;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\Walisantri;
use Carbon\Carbon;
use Closure;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Grid as TableGrid;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\ActionsPosition;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FormulirTahapDua extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    // public static function canView(): bool
    // {
    //     // dd(Auth::user());

    //     $walisantri_id = Walisantri::where('kartu_keluarga_santri', Auth::user()->username)->first();
    //     // dd($walisantri_id->is_collapse);



    //     if ($walisantri_id->is_collapse === true) {
    //         return true;
    //     } elseif ($walisantri_id->is_collapse === false) {
    //         return false;
    //     }

    //     // return auth()->user()->isAdmin();
    // }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Status Upload Dokumen')
            ->description('Scroll/gulir ke kanan untuk melihat status dokumen')
            ->paginated(false)
            ->striped()
            ->query(
                Santri::where('kartu_keluarga', Auth::user()->username)
                    ->where('jenispendaftar', '!=', null)
                    ->where('tahap', 'Tahap 2')
            )
            ->columns([
                TextColumn::make('nama_lengkap')
                    ->label('Nama')
                    ->size(TextColumn\TextColumnSize::Large),

                ImageColumn::make('file_kk')
                    ->label('Kartu Keluarga')
                    // ->circular()
                    ->alignCenter()
                    ->placeholder('Belum Upload'),

                ImageColumn::make('file_akte')
                    ->label('Akte')
                    // ->circular()
                    ->alignCenter()
                    ->placeholder('Belum Upload'),

                ImageColumn::make('file_srs')
                    ->label('Surat Rekomendasi')
                    //->circular()
                    ->alignCenter()
                    ->placeholder('Belum Upload'),

                ImageColumn::make('file_ijz')
                    ->label('Ijazah')
                    //->circular()
                    ->alignCenter()
                    ->placeholder('Belum Upload'),

                ImageColumn::make('file_skt')
                    ->label('Surat Keterangan Taklim')
                    //->circular()
                    ->alignCenter()
                    ->placeholder('Belum Upload'),

                ImageColumn::make('file_skuasa')
                    ->label('Surat Kuasa')
                    //->circular()
                    ->alignCenter()
                    ->placeholder('Belum Upload'),

                ImageColumn::make('file_spkm')
                    ->label('Surat Pernyataan Kesanggupan')
                    //->circular()
                    ->alignCenter()
                    ->placeholder('Belum Upload'),

                ImageColumn::make('file_pka')
                    ->label('Surat Permohonan Keringanan Administrasi')
                    //->circular()
                    ->alignCenter(),

                ImageColumn::make('file_ktmu')
                    ->label('Surat Keterangan Tidak Mampu (U)')
                    //->circular()
                    ->alignCenter(),

                ImageColumn::make('file_ktmp')
                    ->label('Surat Keterangan Tidak Mampu (P)')
                    //->circular()
                    ->alignCenter(),


            ])
            ->defaultSort('nama_lengkap')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Mulai Upload')
                    ->button()
                    // ->outlined()
                    ->icon('heroicon-m-cloud-arrow-up')
                    ->modalHeading('Upload Dokumen')
                    ->modalDescription(new HtmlString('<div class="">
                                                            <p>Butuh bantuan?</p>
                                                            <p>Silakan mengubungi admin di bawah ini:</p>

                                                            <table class="table w-fit">
                                        <!-- head -->
                                        <thead>
                                            <tr class="border-tsn-header">
                                                <th class="text-tsn-header text-xs" colspan="2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- row 1 -->
                                            <tr>
                                                <th><a href="https://wa.me/6282210862400"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                                                </svg>
                                                </a></th>
                                                <td class="text-xs"><a href="https://wa.me/6282210862400">WA Admin Putra (Abu Hammaam)</a></td>
                                            </tr>
                                            <tr>
                                                <th><a href="https://wa.me/6285236459012"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"  fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                                                </svg>
                                                </a></th>
                                                <td class="text-xs"><a href="https://wa.me/6285236459012">WA Admin Putra (Abu Fathimah Hendi)</a></td>
                                            </tr>
                                            <tr>
                                                <th><a href="https://wa.me/6281333838691"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"  fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                                                </svg>
                                                </a></th>
                                                <td class="text-xs"><a href="https://wa.me/6281333838691">WA Admin Putra (Akh Irfan)</a></td>
                                            </tr>
                                            <tr>
                                                <th><a href="https://wa.me/628175765767"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"  fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                                                </svg>
                                                </a></th>
                                                <td class="text-xs"><a href="https://wa.me/628175765767">WA Admin Putri</a></td>
                                            </tr>


                                        </tbody>
                                        </table>

                                                        </div>'))
                    ->modalWidth('full')
                    ->closeModalByClickingAway(false)
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelAction(fn (StaticAction $action) => $action->label('Batal'))
                    ->form([

                        TextInput::make('nama_lengkap')
                            ->label('Nama Santri')
                            ->disabled()
                            ->required(),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                    <p class="text-lg strong"><strong>UPLOAD DOKUMEN</strong></p>
                                                </div>')),

                        Placeholder::make('')
                            ->content(new HtmlString('<div>
                            <table class="table w-fit">
                            <!-- head -->
                            <thead>
                                <tr>
                                    <th class="text-tsn-header text-xs" colspan="2"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                            <th class="text-xs align-top">-</th>
                            <td class="text-xs">Klik tombol "Simpan" di bagian bawah setelah selesai upload atau menghapus dokumen</td>
                            </tr>
                                <tr>
                                    <th class="text-xs align-top">-</th>
                                    <td class="text-xs">Klik "Browse" untuk memilih gambar/foto dokumen</td>
                                </tr>
                                <tr>
                                <th class="text-xs align-top">-</th>
                                <td class="text-xs">Klik tombol "X" untuk menghapus dokumen yang terupload</td>
                                </tr>
                                <tr>
                                <th class="text-xs align-top">-</th>
                                <td class="text-xs">Disarankan untuk upload dokumen satu persatu, dan tunggu sampai muncul tulisan "Upload Complete"</td>
                                </tr>
                                <tr>
                                <th class="text-xs align-top">-</th>
                                <td class="text-xs">Silakan melanjutkan upload dokumen selanjutnya setelah muncul tulisan "Upload Complete"</td>
                                </tr>
                                <tr>
                                <th class="text-xs align-top">-</th>
                                <td class="text-xs">Tulisan "Uploading 100%" artinya masih proses upload!</td>
                                </tr>



                            </tbody>
                            </table>
                                                </div>')),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>')),

                        FileUpload::make('file_kk')
                            ->label('1. Kartu Keluarga')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/kk')
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->kartu_keluarga . "-" . $record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Kartu Keluarga-'),
                            ),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>')),

                        FileUpload::make('file_akte')
                            ->label('2. Akte')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/akte')
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Akte-'),
                            ),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>')),

                        FileUpload::make('file_srs')
                            ->label('3. Surat Rekomendasi')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/srs')
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Surat Rekomendasi-'),
                            ),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>')),

                        FileUpload::make('file_ijz')
                            ->label('4. Ijazah atau Hasil Evaluasi Belajar')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/ijz')
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Ijazah-'),
                            ),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>')),

                        FileUpload::make('file_skt')
                            ->label('5. Surat Keterangan Taklim Orang Tua')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/skt')
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Surat Keterangan Taklim-'),
                            ),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>')),

                        FileUpload::make('file_skuasa')
                            ->label('6. Surat Kuasa dari Orang Tua Kandung')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/skuasa')
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Surat Kuasa-'),
                            ),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>')),

                        FileUpload::make('file_cvd')
                            ->label('7. Sertifikat Vaksin Covid-19 terakhir')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/cvd')
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Sertifikat Vaksin Covid-19-'),
                            ),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>')),

                        FileUpload::make('file_spkm')
                            ->label('8. Surat Pernyataan Kesanggupan (Bermaterai (10000)')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/spkm')
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Surat Penyataan Kesanggupan-'),
                            ),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>'))
                            ->hidden(function (Pendaftar $pendaftar, $record) {

                                $datapendaftar = Pendaftar::where('santri_id', $record->id)->first();

                                if ($datapendaftar->ps_kadm_status === "Santri/Santriwati mampu (tidak ada permasalahan biaya)") {
                                    return true;
                                } else {
                                    return false;
                                }
                            }),

                        FileUpload::make('file_pka')
                            ->label('9. Surat Permohonan Keringanan Administrasi (bagi yang mengajukan keringanan)')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/pka')
                            ->hidden(function (Pendaftar $pendaftar, $record) {

                                $datapendaftar = Pendaftar::where('santri_id', $record->id)->first();

                                if ($datapendaftar->ps_kadm_status === "Santri/Santriwati mampu (tidak ada permasalahan biaya)") {
                                    return true;
                                } else {
                                    return false;
                                }
                            })
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Surat Permohonan Keringanan Administrasi-'),
                            ),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>'))
                            ->hidden(function (Pendaftar $pendaftar, $record) {

                                $datapendaftar = Pendaftar::where('santri_id', $record->id)->first();

                                if ($datapendaftar->ps_kadm_status === "Santri/Santriwati mampu (tidak ada permasalahan biaya)") {
                                    return true;
                                } else {
                                    return false;
                                }
                            }),

                        FileUpload::make('file_ktmu')
                            ->label('10. Surat Keterangan Tidak Mampu dari Ustadz setempat (bagi yang mengajukan keringanan)')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/ktmu')
                            ->hidden(function (Pendaftar $pendaftar, $record) {

                                $datapendaftar = Pendaftar::where('santri_id', $record->id)->first();

                                if ($datapendaftar->ps_kadm_status === "Santri/Santriwati mampu (tidak ada permasalahan biaya)") {
                                    return true;
                                } else {
                                    return false;
                                }
                            })
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Surat Keterangan Tidak Mampu (U)-'),
                            ),

                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                                </div>'))
                            ->hidden(function (Pendaftar $pendaftar, $record) {

                                $datapendaftar = Pendaftar::where('santri_id', $record->id)->first();

                                if ($datapendaftar->ps_kadm_status === "Santri/Santriwati mampu (tidak ada permasalahan biaya)") {
                                    return true;
                                } else {
                                    return false;
                                }
                            }),

                        FileUpload::make('file_ktmp')
                            ->label('11. Surat Keterangan Tidak Mampu dari aparat pemerintah setempat (bagi yang mengajukan keringanan)')
                            ->image()
                            ->maxSize(5024)
                            ->directory('calonsantri/ktmp')
                            ->hidden(function (Pendaftar $pendaftar, $record) {

                                $datapendaftar = Pendaftar::where('santri_id', $record->id)->first();

                                if ($datapendaftar->ps_kadm_status === "Santri/Santriwati mampu (tidak ada permasalahan biaya)") {
                                    return true;
                                } else {
                                    return false;
                                }
                            })
                            ->openable()
                            ->downloadable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, $record): string => (string) str($record->nama_lengkap.".".$file->getClientOriginalExtension())
                                    ->prepend('Surat Keterangan Tidak Mampu (P)-'),
                            ),


                    ])


                    ->after(function ($record) {


                        Notification::make()
                            ->success()
                            ->title('Alhamdulillah data calon santri telah tersimpan')
                            ->body('Lanjutkan upload dokumen calon santri, atau keluar jika telah selesai')
                            ->persistent()
                            ->color('success')
                            ->send();
                    })->modalCloseButton(false),



            ], position: ActionsPosition::BeforeColumns);
    }
}
