<h2>Pengelolaan Data Pertanian</h2>

@if(session('success')) <p style="color:green; font-weight:bold;">{{ session('success') }}</p> @endif
@if(session('error')) <p style="color:red; font-weight:bold;">{{ session('error') }}</p> @endif

<div style="border: 1px solid #333; padding: 15px; background-color: #f9f9f9; max-width: 600px;">
    <h3>Status Produksi Berjalan</h3>
    <p><strong>Tanggal Mulai Tanam:</strong> {{ $schedule->planting_start_date }}</p>
    <p><strong>Musim Tanam (Otomatis Sistem):</strong> <span style="color: blue; font-weight: bold;">{{ $schedule->crop_season }}</span></p>
    <p><strong>Estimasi Hasil Panen (Target):</strong> <span style="color: green; font-weight: bold;">{{ $schedule->estimated_harvest }} Kg</span> (Dihitung otomatis dari akumulasi jumlah bibit yang ditanam)</p>
</div>

<hr>

<h3>Form Penggunaan Stok Sarana Produksi</h3>
<p><i>Isi jumlah pada barang yang ingin Anda gunakan saja (boleh dikosongkan), lalu tekan Simpan di bagian bawah.</i></p>

<form action="/production/{{ $schedule->id }}/usage" method="POST">
    @csrf
    
    <h4>A. Kategori: BIBIT</h4>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; max-width: 600px; margin-bottom: 20px;">
        <thead>
            <tr style="background-color: #eee;">
                <th>Nama Barang</th>
                <th>Sisa Stok</th>
                <th>Tanggal Pakai</th>
                <th>Jumlah Digunakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products->where('category', 'Seed') as $prod)
            <tr>
                <td><strong>{{ $prod->name }}</strong></td>
                <td>{{ $prod->stock_available }} {{ $prod->unit }}</td>
                <td>
                    <input type="date" name="items[{{ $prod->id }}][usage_date]" value="{{ date('Y-m-d') }}" required>
                </td>
                <td>
                    <input type="number" name="items[{{ $prod->id }}][quantity_used]" style="width: 90px;" placeholder="Kosongkan..">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>B. Kategori: PUPUK</h4>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; max-width: 600px; margin-bottom: 20px;">
        <thead>
            <tr style="background-color: #eee;">
                <th>Nama Barang</th>
                <th>Sisa Stok</th>
                <th>Tanggal Pakai</th>
                <th>Jumlah Digunakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products->where('category', 'Fertilizer') as $prod)
            <tr>
                <td><strong>{{ $prod->name }}</strong></td>
                <td>{{ $prod->stock_available }} {{ $prod->unit }}</td>
                <td>
                    <input type="date" name="items[{{ $prod->id }}][usage_date]" value="{{ date('Y-m-d') }}" required>
                </td>
                <td>
                    <input type="number" name="items[{{ $prod->id }}][quantity_used]" style="width: 90px;" placeholder="Kosongkan..">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="submit" style="background-color: blue; color: white; padding: 10px 15px; cursor: pointer; border: none; font-weight: bold;">
        Simpan Pemakaian Massal
    </button>
</form>

<hr>

<fieldset style="max-width: 580px; border: 2px solid red;">
    <legend style="font-weight: bold; color: red;">Form Pencatatan Hasil Panen Akhir</legend>
    <form action="/production/{{ $schedule->id }}/harvest" method="POST">
        @csrf
        <p>Sistem memprediksi hasil panen periode ini adalah: <b>{{ $schedule->estimated_harvest }} Kg</b></p>
        
        <label>Pilih Hasil Produk Padi:</label><br>
        <select name="product_id" required>
            @foreach($products->where('category', 'Rice') as $prod)
                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
            @endforeach
        </select><br><br>

        <label>Tanggal Panen:</label><br>
        <input type="date" name="actual_harvest_date" required><br><br>

        <label>Berat Hasil Panen (Kg):</label><br>
        <input type="number" name="total_harvest" placeholder="Contoh: 520" required><br><br>

        <button type="submit" style="background-color: red; color: white; padding: 8px;">Kunci & Simpan Hasil Panen</button>
    </form>
</fieldset>

<br>
<a href="/">Kembali ke Beranda</a>