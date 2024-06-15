<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangkeluar;
use App\Models\Barang;

class BarangkeluarController extends Controller
{
    public function index()
    {
        // Mengambil data dari tabel 'barangkeluar' menggunakan Eloquent ORM dengan relasi ke 'barang'
        $barangkeluar = Barangkeluar::with('barang')->latest()->paginate(10);

        return view('v_barangkeluar.index', compact('barangkeluar'));
    }

    public function create()
    {
        $merkBarang = Barang::pluck('merk', 'id');
        // Menampilkan form untuk membuat data barang keluar
        return view('v_barangkeluar.create', compact('merkBarang'));
    }

    public function store(Request $request)
    {
        // Validasi data dari request jika diperlukan
        $validatedData = $request->validate([
            'tgl_keluar' => 'required',
            'qty_keluar' => 'required|numeric|min:0',
            'barang_id' => 'required',
        ]);

        // Cari barang yang akan dikurangi stoknya
        $barang = Barang::find($request->barang_id);

        // Validasi jika jumlah qty_keluar lebih besar dari stok saat ini maka muncul pesan error
        if ($request->qty_keluar > $barang->stok) {
            return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah barang keluar melebihi stok!']);
        }

        $barangMasuk = $barang->barangMasuk()->latest()->first();
        if ($barangMasuk && $request->tgl_keluar < $barangMasuk->tgl_masuk) {
            return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal keluar tidak boleh sebelum tanggal masuk barang!']);
        }

        // Simpan data barang keluar baru ke database
        $barangKeluar = new Barangkeluar();
        $barangKeluar->tgl_keluar = $request->tgl_keluar;
        $barangKeluar->qty_keluar = $request->qty_keluar;
        $barangKeluar->barang_id = $request->barang_id;
        $barangKeluar->save();

        // Kurangi stok barang
        $barang->stok -= $request->qty_keluar;
        $barang->save();

        return redirect()->route('barangkeluar.index')->with('success', 'Barang keluar berhasil ditambahkan');
    }

    public function show($id)
    {
        // Mengambil data barang keluar berdasarkan ID
        $barangkeluar = Barangkeluar::findOrFail($id);
    
        return view('v_barangkeluar.show', compact('barangkeluar'));
    }

    public function edit($id)
    {
        // Mengambil data barang keluar untuk diedit berdasarkan ID
        $barangkeluar = Barangkeluar::findOrFail($id);
        $merkBarang = Barang::pluck('merk', 'id');
    
        return view('v_barangkeluar.edit', compact('barangkeluar', 'merkBarang'));
    }
    
    public function update(Request $request, $id)
    {
        // Validasi data dari request jika diperlukan
        $validatedData = $request->validate([
            'tgl_keluar' => 'required',
            'qty_keluar' => 'required|numeric|min:0',
        ]);
    
        // Mengambil data barang keluar yang akan diupdate
        $barangkeluar = Barangkeluar::findOrFail($id);

        // Cari barang yang stoknya akan diperbarui
        $barang = Barang::find($request->barang_id);

        // Hitung perbedaan jumlah barang keluar
        $qty_diff = $request->qty_keluar - $barangkeluar->qty_keluar;

        // Validasi jika perubahan jumlah barang keluar melebihi stok yang tersedia
        if ($qty_diff > 0 && $qty_diff > $barang->stok) {
            return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Perubahan jumlah barang keluar melebihi stok yang tersedia!']);
        }

        $barangMasuk = $barang->barangMasuk()->latest()->first();
        if ($barangMasuk && $request->tgl_keluar < $barangMasuk->tgl_masuk) {
            return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal keluar tidak boleh sebelum tanggal masuk barang!']);
        }


        // Perbarui data barang keluar
        $barangkeluar->tgl_keluar = $request->tgl_keluar;
        $barangkeluar->qty_keluar = $request->qty_keluar;
        $barangkeluar->save();

        // Perbarui stok barang
        $barang->stok -= $qty_diff;
        $barang->save();
    
        return redirect()->route('barangkeluar.index')->with('success', 'Barang keluar berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        // Mengambil data barang keluar yang akan dihapus
        $barangkeluar = Barangkeluar::findOrFail($id);

        // Kembalikan stok barang yang dihapus
        $barang = Barang::find($barangkeluar->barang_id);
        $barang->stok += $barangkeluar->qty_keluar;
        $barang->save();

        // Hapus data barang keluar
        $barangkeluar->delete();
    
        return redirect()->route('barangkeluar.index')->with('success', 'Barang keluar berhasil dihapus');
    }
}