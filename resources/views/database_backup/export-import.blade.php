@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/database_backups/export-import.css') }}">
@endpush

@section('title', 'Pencadangan dan Pemulihan Database')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <button onclick="if(typeof navigateWithFullPageLoading === 'function') { navigateWithFullPageLoading('{{ route('penghuni.index') }}', 'Kembali ke data penghuni...'); } else { window.location.href = '{{ route('penghuni.index') }}'; }" 
                class="text-primary hover:underline font-semibold flex items-center gap-2 inline-flex transition-all hover:gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Manajemen Penghuni
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-md p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pencadangan dan Pemulihan Database</h1>
            <p class="text-gray-600">
                Kelola backup database Anda. File backup akan disimpan dengan timestamp untuk penanda waktu data.
            </p>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 mb-8">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-bold text-blue-900 mb-2">ℹ️ Cara Kerja Export & Import</p>
                    <ul class="text-blue-800 text-sm space-y-2">
                        <li>📽 <strong>Export:</strong> File database akan diunduh dengan nama: <code class="bg-blue-100 px-2 py-1 rounded">backup_YYYY-MM-DD_HH-mm-ss.sql</code></li>
                        <li>📼 <strong>Import:</strong> Database saat ini akan di-backup otomatis dengan nama: <code class="bg-blue-100 px-2 py-1 rounded">backup_before_import_[timestamp].sql</code></li>
                        <li>💾 <strong>Penyimpanan:</strong> File import akan disimpan sebagai referensi: <code class="bg-blue-100 px-2 py-1 rounded">import_[timestamp].sql</code></li>
                        <li>⚠️ <strong>Keamanan:</strong> File lama otomatis di-rename dengan timestamp, tidak akan ditimpa</li>
                        <li>📏 <strong>Limit:</strong> Maksimal ukuran file upload: <strong>100 MB</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <div class="flex gap-4 border-b">
                <button onclick="DatabaseBackup.switchTab('tab-export')" 
                        id="btn-tab-export"
                        class="py-3 px-4 font-semibold text-primary border-b-2 border-primary tab-btn">
                    📥 Export Database
                </button>
                <button onclick="DatabaseBackup.switchTab('tab-import')" 
                        id="btn-tab-import"
                        class="py-3 px-4 font-semibold text-gray-600 hover:text-gray-800 tab-btn">
                    📤 Import Database
                </button>
            </div>
        </div>

        <div id="tab-export" class="tab-content">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 mb-6">
                <div class="flex items-start gap-4 justify-between">
                    <div class="flex-1">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-shrink-0">
                                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-blue-900 mb-2">Download Backup Database</h3>
                                <p class="text-blue-800">
                                    Klik tombol di bawah untuk mengunduh backup database lengkap dalam format .sql. File akan otomatis disimpan di folder backup dengan timestamp.
                                </p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('db-backup.export-download') }}" 
                       class="inline-flex items-center gap-2 bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition-all flex-shrink-0 whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export Database Sekarang
                    </a>
                </div>
            </div>
        </div>

        <div id="tab-import" class="tab-content hidden">
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6 mb-6">
                <div class="flex items-start gap-4 justify-between">
                    <div class="flex-1">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="flex-shrink-0">
                                <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-purple-900 mb-2">Restore Database dari Backup</h3>
                                <p class="text-purple-800">
                                    Unggah file backup .sql untuk merestorasi database. Database saat ini akan di-backup otomatis sebelum proses restore dimulai.
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('db-backup.import') }}" method="POST" enctype="multipart/form-data" id="importForm" class="space-y-4">
                            @csrf
                            <div id="dropZone" class="border-2 border-dashed border-purple-300 rounded-xl p-8 text-center bg-white hover:bg-purple-50 transition-colors cursor-pointer">
                                <svg class="w-16 h-16 text-purple-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6"/>
                                </svg>
                                <p class="text-gray-700 font-semibold mb-2">Drag file .sql ke sini atau klik untuk memilih</p>
                                <p class="text-gray-500 text-sm">Maksimal 100 MB</p>                                
                                <input type="file" 
                                    id="file" 
                                    name="sql_file" 
                                    accept=".sql" 
                                    style="position: absolute; width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; z-index: -1;" />
                                <div id="fileName" class="mt-4"></div>
                            </div>
                            
                            <div class="flex gap-3 justify-end">
                                <button type="button"
                                        onclick="if(typeof navigateWithFullPageLoading === 'function') { navigateWithFullPageLoading('{{ route('penghuni.index') }}', 'Kembali ke data penghuni...'); } else { window.location.href = '{{ route('penghuni.index') }}'; }"
                                        class="flex items-center gap-2 bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg hover:bg-gray-400 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="flex items-center gap-2 bg-purple-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-purple-700 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Import Database
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($backups && count($backups) > 0)
        <div class="mt-12 pt-8 border-t">
            <h2 class="text-xl font-bold text-gray-800 mb-4">📁 File Backup yang Tersedia</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="p-3 text-left font-semibold text-gray-700">Nama File</th>
                            <th class="p-3 text-left font-semibold text-gray-700 w-24">Ukuran</th>
                            <th class="p-3 text-left font-semibold text-gray-700">Tanggal</th>
                            <th class="p-3 text-center font-semibold text-gray-700 w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $backup)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="p-3 font-mono text-gray-800 text-xs break-all">{{ $backup['name'] }}</td>
                            <td class="p-3 text-gray-600 whitespace-nowrap">{{ $backup['size'] }}</td>
                            <td class="p-3 text-gray-600 whitespace-nowrap">{{ $backup['date'] }}</td>
                            <td class="p-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('db-backup.download', ['file' => urlencode($backup['name'])]) }}" 
                                       class="bg-blue-600 text-white text-xs font-bold py-1.5 px-3 rounded hover:bg-blue-700 transition-all"
                                       title="Download file">
                                        Download
                                    </a>
                                    <button onclick="DatabaseBackup.openDeleteModal('{{ $backup['name'] }}', '{{ route('db-backup.delete', ['file' => urlencode($backup['name'])]) }}')"
                                       class="bg-red-600 text-white text-xs font-bold py-1.5 px-3 rounded hover:bg-red-700 transition-all"
                                       title="Hapus file">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="text-xs text-gray-500 mt-3">
                💡 Tip: Download file backup untuk menyimpannya di tempat yang aman, atau hapus jika sudah tidak diperlukan.
            </p>
        </div>
        @else
        <div class="mt-12 pt-8 border-t text-center py-8">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 1112 2.944a11.954 11.954 0 018.618 3.04A12.02 12.02 0 1121 12z"/>
            </svg>
            <p class="text-gray-500 font-semibold">Belum ada file backup</p>
            <p class="text-gray-400 text-sm mt-1">File backup akan muncul di sini setelah Anda melakukan export atau import</p>
        </div>
        @endif
    </div>
</div>

<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 modal-content">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Hapus File Backup?</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus file: <br><span class="font-mono text-sm font-semibold text-gray-800" id="deleteFileName"></span></p>
            <p class="text-2xl mb-1">⚠️</p>
            <p class="text-sm font-bold">Perhatikan!</p> 
            <p class="text-sm">Tindakan ini akan menghapus file secara permanen!</p>
            
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('GET')
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('deleteModal')" class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-red-700 transition-all">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/database_backups/export-import.js') }}"></script>

@if(session('download_url'))
<script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.location.href = '{{ session("download_url") }}';
        }, 1000);
    });
</script>
@endif
@endpush
@endsection