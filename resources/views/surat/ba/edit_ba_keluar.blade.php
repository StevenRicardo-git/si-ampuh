@extends('layouts.app')

@section('title', 'Edit Berita Acara Keluar')

@section('content')

<style>
    * {
        text-decoration: none !important;
    }
</style>

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Berita Acara Keluar</h1>
        <p class="text-gray-500 mt-1">Penghuni: <span class="font-bold">{{ $kontrak->penghuni->nama }}</span> - Unit: <span class="font-bold">{{ $kontrak->unit->kode_unit }}</span></p>
    </div>
    <a href="{{ route('penghuni.show', $kontrak->penghuni_id) }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded-lg hover:bg-gray-300 transition-all flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
</div>

@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
        <div class="flex">
            <svg class="h-6 w-6 text-red-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    </div>
@endif

<form action="{{ route('kontrak.generatePdfBaKeluar', $kontrak->id) }}" method="POST" class="bg-white p-6 rounded-xl shadow-md" target="_blank">
    @csrf

    <div class="mb-6 pb-6 border-b">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Penghuni</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penghuni</label>
                <input type="text" value="{{ $formData['penghuni_nama'] }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                <input type="text" value="{{ $formData['unit_kode'] }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" readonly>
            </div>
        </div>
    </div>

    <div class="mb-6 pb-6 border-b">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Nomor Berita Acara</h2>
        <div>
            <label for="nomor_ba" class="block text-sm font-medium text-gray-700 mb-2">
                Nomor BA *
            </label>
            <input type="text" name="nomor_ba" id="nomor_ba" 
                value="{{ old('nomor_ba', $formData['nomor_ba'] ?? '') }}" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('nomor_ba') border-red-500 @enderror" 
                placeholder="Contoh: 600/123/2025">
            @error('nomor_ba')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mb-6 pb-6 border-b">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Tanggal</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="tanggal_pemutusan_perjanjian_sewa" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Pemutusan Perjanjian Sewa *
                </label>
                <input type="text" name="tanggal_pemutusan_perjanjian_sewa" id="tanggal_pemutusan_perjanjian_sewa" 
                    value="{{ $formData['tanggal_pemutusan_perjanjian_sewa'] }}" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 @error('tanggal_pemutusan_perjanjian_sewa') border-red-500 @enderror" 
                    placeholder="Contoh: Senin, 30 September 2025" readonly>
                @error('tanggal_pemutusan_perjanjian_sewa')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tanggal_ba" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Berita Acara *
                </label>
                <input type="text" name="tanggal_ba" id="tanggal_ba" 
                    value="{{ $formData['tanggal_ba'] }}" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('tanggal_ba') border-red-500 @enderror" 
                    placeholder="Contoh: Senin, 30 September 2025">
                @error('tanggal_ba')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <div class="mb-6 pb-6 border-b">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Alasan Keluar</h2>
        <label for="alasan_keluar" class="block text-sm font-medium text-gray-700 mb-2">Alasan Keluar *</label>
        <textarea name="alasan_keluar" id="alasan_keluar" rows="3" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('alasan_keluar') border-red-500 @enderror" 
            placeholder="Masukkan alasan penghuni keluar">{{ old('alasan_keluar', $formData['alasan_keluar']) }}</textarea>
        @error('alasan_keluar')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6 pb-6 border-b">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Rincian Tunggakan (Jika Ada)</h2>
            <span class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">Denda: 10% dari Sewa</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="tunggakan_sewa" class="block text-sm font-medium text-gray-700 mb-2">Sewa Bulanan (Rp)</label>
                <input type="text" name="tunggakan_sewa" id="tunggakan_sewa" 
                    value="{{ old('tunggakan_sewa', number_format($formData['tunggakan_sewa'], 0, ',', '.')) }}" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('tunggakan_sewa') border-red-500 @enderror" 
                    placeholder="0">
                @error('tunggakan_sewa')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="periode_tunggakan_sewa" class="block text-sm font-medium text-gray-700 mb-2">Keterangan Sewa</label>
                <input type="text" name="periode_tunggakan_sewa" id="periode_tunggakan_sewa" 
                    value="{{ old('periode_tunggakan_sewa', $formData['periode_tunggakan_sewa']) }}" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('periode_tunggakan_sewa') border-red-500 @enderror" 
                    placeholder="Contoh: Juli s.d September 2025">
                @error('periode_tunggakan_sewa')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="tunggakan_air" class="block text-sm font-medium text-gray-700 mb-2">Biaya Pemakaian Air (Rp)</label>
                <input type="text" name="tunggakan_air" id="tunggakan_air" 
                    value="{{ old('tunggakan_air', number_format($formData['tunggakan_air'], 0, ',', '.')) }}" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('tunggakan_air') border-red-500 @enderror" 
                    placeholder="0">
                @error('tunggakan_air')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="periode_tunggakan_air" class="block text-sm font-medium text-gray-700 mb-2">Keterangan Air</label>
                <input type="text" name="periode_tunggakan_air" id="periode_tunggakan_air" 
                    value="{{ old('periode_tunggakan_air', $formData['periode_tunggakan_air'] ?? '') }}" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('periode_tunggakan_air') border-red-500 @enderror" 
                    placeholder="Contoh: Periode Agustus 2025">
                @error('periode_tunggakan_air')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tunggakan_listrik" class="block text-sm font-medium text-gray-700 mb-2">Biaya Pemakaian Listrik (Rp)</label>
                <input type="text" name="tunggakan_listrik" id="tunggakan_listrik" 
                    value="{{ old('tunggakan_listrik', number_format($formData['tunggakan_listrik'], 0, ',', '.')) }}" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('tunggakan_listrik') border-red-500 @enderror" 
                    placeholder="0">
                @error('tunggakan_listrik')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="periode_tunggakan_listrik" class="block text-sm font-medium text-gray-700 mb-2">Keterangan Listrik</label>
                <input type="text" name="periode_tunggakan_listrik" id="periode_tunggakan_listrik" 
                    value="{{ old('periode_tunggakan_listrik', $formData['periode_tunggakan_listrik'] ?? '') }}" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('periode_tunggakan_listrik') border-red-500 @enderror" 
                    placeholder="Contoh: Periode Agustus 2025">
                @error('periode_tunggakan_listrik')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Kepala Dinas yang akan ditampilkan</h2>
            <a href="{{ route('disperkim.index') }}" target="_blank" class="text-primary hover:underline text-sm flex items-center gap-1">
                Kelola Data Kepala Dinas
            </a>
        </div>
        
        @if($kepalaDinas)
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 font-semibold uppercase mb-1">Nama</label>
                    <p class="text-gray-900 font-medium">{{ $kepalaDinas->nama }}</p>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 font-semibold uppercase mb-1">NIP</label>
                    <p class="text-gray-900 font-medium">{{ $kepalaDinas->nip ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs text-gray-500 font-semibold uppercase mb-1">Jabatan</label>
                    <p class="text-gray-900 font-medium">{{ $kepalaDinas->jabatan }}</p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
            <strong>Error!</strong> Data Kepala Dinas tidak ditemukan.
        </div>
        @endif
    </div>

    <div class="mb-6 pb-6 border-b">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Staff yang akan ditampilkan</h2>
            <a href="{{ route('disperkim.index') }}" target="_blank" class="text-primary hover:underline text-sm flex items-center gap-1">
                Kelola Data Staff
            </a>
        </div>

        @if($staff && $staff->count() >= 1)
        <div class="overflow-x-auto">
            <table class="text-center w-full text-left text-sm">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $index => $s)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-medium">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $s->nama }}</td>
                        <td class="p-3 text-gray-600">{{ $s->jabatan }}</td>
                    </tr>
                    @endforeach
                    <tr class="border-b hover:bg-gray-50 bg-blue-50">
                        <td class="p-3 font-medium">{{ $staff->count() + 1 }}</td>
                        <td class="p-3 font-semibold">{{ $kontrak->penghuni->nama }}</td>
                        <td class="p-3 text-gray-600">Penghuni Rusunawa Blok {{ $kontrak->unit->kode_unit }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg">
            <strong>Peringatan!</strong> Data Staff minimal 1 orang.
        </div>
        @endif
    </div>

    <div class="flex gap-3 mt-8 pt-6 border-t">
        <a href="{{ route('penghuni.show', $kontrak->penghuni_id) }}" class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 transition-all text-center inline-flex items-center justify-center">
            Batal
        </a>
        <button type="submit" class="flex-1 bg-primary text-white font-bold py-3 px-6 rounded-lg hover:bg-opacity-90 transition-all flex items-center justify-center">
            Download PDF BA Keluar
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function formatCurrency(input) {
        let rawValue = input.value.replace(/\D/g, '');

        if (rawValue === '' || parseInt(rawValue) === 0) {
            input.value = '';
            return;
        }

        let formatted = parseInt(rawValue, 10).toLocaleString('id-ID');
        
        input.value = formatted;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const currencyInputs = ['tunggakan_sewa', 'tunggakan_air', 'tunggakan_listrik'];
        
        currencyInputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', function() {
                    formatCurrency(this);
                });
                input.addEventListener('blur', function() {
                    formatCurrency(this);
                }); 
                formatCurrency(input);
            }
        });
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(event) {
                const currencyInputs = ['tunggakan_sewa', 'tunggakan_air', 'tunggakan_listrik'];
                currencyInputs.forEach(id => {
                    const input = document.getElementById(id);
                    if (input && input.value) {
                        input.value = input.value.replace(/\D/g, '');
                    }
                });
            });
        }
    });
</script>
@endpush