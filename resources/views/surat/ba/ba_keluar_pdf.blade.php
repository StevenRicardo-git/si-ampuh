<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat BA Keluar {{ $penghuni->nama }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        
        @page {
            margin: 1cm 2.5cm;
        }

        .spacer-top {
            margin-top: 1cm; 
        }

        .page-break {
            page-break-after: always;
            height: 0;
            display: block;
            visibility: hidden;
        }
        
        .container {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }
        .header .logo-kiri {
            max-height: 75px;
            float: left;
            margin-right: 20px;
        }
        .kop-surat-teks { text-align: center; }
        .header h1 { font-size: 14pt; font-weight: bold; margin: 0; line-height: 1.2; }
        .header h2 { font-size: 16pt; font-weight: bold; margin: 0; line-height: 1.2; }
        .header p { font-size: 12pt; margin: 0; line-height: 1.2; }
        .clear { clear: both; }
        .line-divider {
            border-bottom: 3px solid black;
            margin-top: 8px;
            margin-bottom: 10px;
            margin-left: -1.3cm;  
            margin-right: -1.3cm;
        }
   
        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 10px;
            margin-bottom: 4px;
            line-height: 1.1;
        }
        .title-no-underline {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 4px;
        }
        .subtitle {
            text-align: center;
            font-size: 12pt;
            margin-bottom: 10px;
            line-height: 1.2;
            margin-top: 0;
            margin-bottom: 2px;
        }
     
        .content {
            margin-top: 10px;
            text-align: justify;
        }
        .content p {
            margin-bottom: 6px;
            margin-left: 0;
            margin-right: 0;
        }

        .data-table {
            width: 100%;
            margin-top: 5px;
            margin-bottom: 5px;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 1px 0;
            vertical-align: top;
        }
        .data-table td:first-child {
            width: 170px;
        }
        .data-table td:nth-child(2) {
            width: 10px;
        }

        .table-data-with-no {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 12pt;
        }
        .table-data-with-no th, .table-data-with-no td {
            border: 1px solid black;
            text-align: left;
            vertical-align: middle;
        }
        .table-data-with-no td {
            padding: 25px 8px; 
        }
        .table-data-with-no th {
            text-align: center;
            font-size: 12pt;
            padding: 8px 4px;
        }
        .table-data-with-no th:first-child, .table-data-with-no td:first-child { 
            width: 10px; 
            text-align: center; 
        }

        .table-data-with-no th:nth-child(2), .table-data-with-no td:nth-child(3) { 
            width: 240px;
            text-align: center;
            font-size: 11pt;
        }
        .table-data-with-no th:nth-child(3), .table-data-with-no td:nth-child(3) { 
            width: 200px;
            text-align: center;
            font-size: 11pt;
        }
        .table-data-with-no th:last-child, .table-data-with-no td:last-child { 
            width: 120px;
            text-align: center;
            font-size: 11pt;
        }
        
        .table-tunggakan {
            width: 100%;
            margin-top: 8px;
            margin-bottom: 8px;
            border-collapse: collapse;
        }
        .table-tunggakan td { 
            padding: 3px 0;
            vertical-align: top;
        }
        .table-tunggakan td:first-child { 
            width: 230px; 
            text-align: left;
        }
        .table-tunggakan td:nth-child(2) { 
            width: 15px;
            text-align: center;
        }
        .table-tunggakan td:nth-child(3) { 
            width: 25px;
            text-align: left;
        }
        .table-tunggakan td:nth-child(4) { 
            width: 90px;
            text-align: right;
            padding-right: 5px;
        }
        .table-tunggakan td:nth-child(5) { 
            text-align: left;
            padding-left: 5px;
        }

        .signature-right {
            float: right;
            width: 250px;
            text-align: center;
            margin-top: 30px;
        }
        .signature-right p {
            margin: 0;
            line-height: 1.3;
            text-align: center;
        }
        .signature-right .signature-name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
            text-align: center;
        }

        .dots-manual {
            letter-spacing: 2px;
        }

        .dots-date-container {
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .signature-kepala-dinas {
            width: 350px;
            margin-left: auto;
            margin-top: 15px;
            text-align: center;
        }
        .signature-kepala-dinas p {
            margin: 0;
            line-height: 1.3;
        }
        .signature-kepala-dinas .nama-kepala {
            margin-top: 55px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/disperkimkota.png') }}" class="logo-kiri" alt="Logo Pemkot">
            <div class="kop-surat-teks">
                <h1>PEMERINTAH KOTA TEGAL</h1>
                <h2>DINAS PERUMAHAN DAN KAWASAN PERMUKIMAN</h2>
                <p>Jalan Ki Gede Sebayu Nomor 12 Kota Tegal</p>
                <p>Telepon (0283) 358165 Faks. (0283) 353673 Kode Pos 52123</p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="line-divider"></div>

        <p class="title">BERITA ACARA PEMUTUSAN PERJANJIAN SEWA MENYEWA</p>
        <p class="title" style="margin-top: 0;">RUMAH SUSUN SEDERHANA SEWA KOTA TEGAL</p>
        <p class="subtitle">Nomor : {{ $form['nomor_ba'] }}</p>

        <div class="content">
            <p>Pada hari ini, {{ $form['tanggal_pemutusan_spelled_out'] }} ( {{ $form['tanggal_pemutusan_numeric'] }} ), telah sepakat dilakukan pemutusan perjanjian sewa-menyewa atas nama :</p>

            <table class="data-table">
                <tr><td>Nama</td><td>:</td><td><strong>{{ $penghuni->nama }}</strong></td></tr>
                <tr><td>BLOK</td><td>:</td><td>{{ $unit->kode_unit }}</td></tr>
                <tr><td>No. KTP</td><td>:</td><td>{{ $penghuni->nik }}</td></tr>
                <tr><td>Pekerjaan</td><td>:</td><td>{{ $penghuni->pekerjaan ?? '-' }}</td></tr>
                <tr><td>No. S.I.P</td><td>:</td><td>{{ $kontrak->no_sip ?? '-' }}</td></tr>
                <tr><td>No. Perjanjian Sewa</td><td>:</td><td>{{ $kontrak->no_sps ?? '-' }}</td></tr>
            </table>

            <p>Karena yang bersangkutan menyatakan mundur {{ $form['alasan_keluar'] }}.</p>
            <p>Demikian Berita Acara ini dibuat dengan sesungguhnya dan penuh rasa tanggung jawab.</p>

            <table class="table-data-with-no">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff_list as $index => $s)
                    <tr>
                        <td>{{ $index + 1 }}.</td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->jabatan }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>{{ $staff_list->count() + 1 }}.</td>
                        <td>{{ $penghuni->nama }}</td>
                        <td>Penghuni Rusunawa Blok {{ $unit->kode_unit }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <br>
            <div class="signature-kepala-dinas">
                <p>Plt. KEPALA DINAS PERUMAHAN DAN</p>
                <p>KAWASAN PERMUKIMAN KOTA TEGAL</p>
                <br><br>
                <p class="nama-kepala">{{ $kepala_dinas_nama }}</p>
                <p>{{ $kepala_dinas_pangkat }}</p>
                <p>NIP. {{ $kepala_dinas_nip }}</p>
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="container spacer-top">
        <p class="title-no-underline" style="margin-top: 30px; margin-bottom: 35px;">TANDA TERIMA UANG JAMINAN<br>KEPADA PENGHUNI YANG KELUAR DARI HUNIAN RUSUNAWA</p>

        <div class="content">
            <p>Yang bertanda tangan di bawah ini :</p>
            
            <table class="data-table">
                <tr><td>Nama</td><td>:</td><td>{{ $penghuni->nama }}</td></tr>
                <tr><td>BLOK</td><td>:</td><td>{{ $unit->kode_unit }}</td></tr>
                <tr><td>No. KTP</td><td>:</td><td>{{ $penghuni->nik }}</td></tr>
                <tr><td>Alamat KTP</td><td>:</td><td>{{ $penghuni->alamat_ktp ?? '-' }}</td></tr>
            </table>

            <p style="margin-top: 15px;">
                Telah menerima kembali uang jaminan yang pernah disetorkan dikarenakan keluar dari
                hunian Rusunawa sebesar Rp {{ number_format($nilai_jaminan, 0, ',', '.') }}
                @if($nilai_jaminan == 0)
                    ( Nol Rupiah )
                @else
                    ( {{ ucwords(trim($jaminan_terbilang)) }} Rupiah )
                @endif
            </p>

            @php
                $tanggal_ba_clean = $form['tanggal_ba'];
                if (str_contains($tanggal_ba_clean, ',')) {
                    $parts = explode(',', $tanggal_ba_clean);
                    $tanggal_ba_clean = trim(end($parts));
                }
            @endphp

            <div class="signature-right">
                <p>Tegal, {{ $tanggal_ba_clean }}</p>
                <p>Penerima</p>
                <br><br>
                <p class="signature-name">{{ $penghuni->nama }}</p>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="container spacer-top">
        <p class="title-no-underline" style="margin-top: 30px; margin-bottom: 35px;">SURAT PERNYATAAN PEMBAYARAN TUNGGAKAN RUSUNAWA</p>

        <div class="content">
            <p>Yang bertanda tangan di bawah ini :</p>
            
            <table class="data-table">
                <tr><td>Nama</td><td>:</td><td>{{ $penghuni->nama }}</td></tr>
                <tr><td>BLOK</td><td>:</td><td>{{ $unit->kode_unit }}</td></tr>
                <tr><td>No. KTP</td><td>:</td><td>{{ $penghuni->nik }}</td></tr>
                <tr><td>Alamat KTP</td><td>:</td><td>{{ $penghuni->alamat_ktp ?? '-' }}</td></tr>
            </table>

            <p style="margin-top: 15px;">Menyatakan dengan penuh kesadaran bahwa saya mengakui masih memiliki tunggakan pembayaran hunian Rusunawa dengan rincian sebagai berikut :</p>
            
            <table class="table-tunggakan" style="margin-top: 12px;">
                <tr>
                    <td>A. Sewa Bulanan</td>
                    <td>:</td>
                    <td>Rp.</td>
                    <td>{{ $form['tunggakan_sewa'] > 0 ? number_format($form['tunggakan_sewa'], 0, ',', '.') : '-' }}</td>
                    <td>
                        @if($form['tunggakan_sewa'] > 0)
                            {{ $form['periode_tunggakan_sewa'] ? '(' . $form['periode_tunggakan_sewa'] . ')' : '' }}
                        @else
                            (0)
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td style="padding-left: 18px;">Denda</td> <td>:</td>
                    <td>Rp.</td>
                    <td>{{ $form['tunggakan_denda'] > 0 ? number_format($form['tunggakan_denda'], 0, ',', '.') : '-' }}</td>
                    <td>
                        @if($form['tunggakan_denda'] <= 0)
                            (0)
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td>B. Biaya Pemakaian Air</td>
                    <td>:</td>
                    <td>Rp.</td>
                    <td>{{ $form['tunggakan_air'] > 0 ? number_format($form['tunggakan_air'], 0, ',', '.') : '-' }}</td>
                    <td>
                        @if($form['tunggakan_air'] > 0)
                            {{ $form['periode_tunggakan_air'] ? '(' . $form['periode_tunggakan_air'] . ')' : '' }}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td>C. Biaya Pemakaian Listrik</td>
                    <td>:</td>
                    <td>Rp.</td>
                    <td>{{ $form['tunggakan_listrik'] > 0 ? number_format($form['tunggakan_listrik'], 0, ',', '.') : '-' }}</td>
                    <td>
                        @if($form['tunggakan_listrik'] > 0)
                            {{ $form['periode_tunggakan_listrik'] ? '(' . $form['periode_tunggakan_listrik'] . ')' : '' }}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td colspan="5" style="height: 15px;"></td>
                </tr>

                <tr>
                    <td>Jumlah Tunggakan</td>
                    <td>:</td>
                    <td>Rp.</td>
                    <td>{{ number_format($jumlah_tunggakan, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Uang Jaminan</td>
                    <td>:</td>
                    <td>Rp.</td>
                    <td>{{ number_format($nilai_jaminan, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Sisa Tunggakan</td>
                    <td>:</td>
                    <td style="font-weight: bold;">Rp.</td>
                    <td style="font-weight: bold;">{{ number_format($sisa_tunggakan, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </table>

            <p style="margin-top: 20px;">
                Atas tunggakan tersebut, saya bersedia melunasi tunggakan tersebut pada tanggal
            </p>
            <p class="dots-date-container">
                <span class="dots-manual">...........................................................................</span>
            </p>

            <p>Apabila sampai dengan batas waktu yang telah ditetapkan Saya tidak dapat melunasi tunggakan tersebut, saya bersedia menerima sanksi dan masuk dalam daftar hitam calon penghuni Rusunawa di Kota Tegal di kemudian hari.</p>
            <p>Demikian Surat Pernyataan ini Saya buat dengan sebenar-benarnya.</p>

            <div class="signature-right">
                <p>Tegal, <span class="dots-manual">...........................</span></p>
                <p>Yang Menyatakan,</p>
                <br><br>
                <p class="signature-name">{{ $penghuni->nama }}</p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>