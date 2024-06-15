<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Barangmasuk;
use App\Models\Barangkeluar;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class BarangmasukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tgl_masuk = $request->input('tgl_masuk');
        $qty_masuk = $request->input('qty_masuk');

        // Ambil semua barangmasuk dengan pencarian
        $barangmasuk = BarangMasuk::with('barang')
            ->when($search, function ($query, $search) {
                return $query->whereHas('barang', function ($query) use ($search) {
                    $query->where('merk', 'like', '%' . $search . '%')
                        ->orWhere('seri', 'like', '%' . $search . '%')
                        ->orWhereHas('kategori', function ($query) use ($search) {
                            $query->where('deskripsi', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($tgl_masuk, function ($query, $tgl_masuk) {
                return $query->whereDate('tgl_masuk', $tgl_masuk);
            })
            ->when($qty_masuk, function ($query, $qty_masuk) {
                return $query->where('qty_masuk', $qty_masuk);
            })
            ->latest()
            ->paginate(2);

            $barangmasuk->appends(['search' => $search, 'tgl_masuk' => $tgl_masuk, 'qty_masuk' => $qty_masuk]);


        return view('v_barangmasuk.index', compact('barangmasuk', 'search', 'tgl_masuk', 'qty_masuk'));
    }


    public function create()
    {
        $merkBarang = Barang::pluck('merk', 'id');
        // Menampilkan form untuk membuat data barang masuk
        return view('v_barangmasuk.create', compact('merkBarang'));
    }

    public function store(Request $request)
    {
        // Validasi data dari request jika diperlukan
        $validatedData = $request->validate([
            'tgl_masuk' => 'required',
            'qty_masuk' => 'required|numeric|min:0',
            'barang_id' => 'required',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);

        // $barang = Barang::find($request->barang_id);

        // //Validasi jika jumlah qty_keluar lebih besar dari stok saat itu maka muncul pesan eror
        // if ($request->qty_keluar > $barang->stok) {
        //     return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah barang keluar melebihi stok!']);
        // }


        // Simpan data barang masuk baru ke database
        $barangMasuk = new Barangmasuk();
        $barangMasuk->tgl_masuk = $request->tgl_masuk;
        $barangMasuk->qty_masuk = $request->qty_masuk;
        $barangMasuk->barang_id = $request->barang_id;
        // Tambahkan kolom lainnya yang perlu diisi
        $barangMasuk->save();

        return redirect()->route('barangmasuk.index')->with('success', 'Barang masuk berhasil ditambahkan');
    }

    public function show($id)
    {
        // Mengambil data barang masuk berdasarkan ID
        $barangmasuk = Barangmasuk::findOrFail($id);
    
        return view('v_barangmasuk.show', compact('barangmasuk'));
    }

    public function edit($id)
    {
        // Mengambil data barang masuk untuk diedit berdasarkan ID
        $barangmasuk = Barangmasuk::findOrFail($id);
        $merkBarang = Barang::pluck('merk', 'id');
        //$abarangmasuk = Barang::all();

    
        return view('v_barangmasuk.edit', compact('barangmasuk', 'merkBarang'));
    }
    
    public function update(Request $request, $id)
    {
        // Validasi data dari request jika diperlukan
        $validatedData = $request->validate([
            'tgl_masuk' => 'required',
            'qty_masuk' => 'required|numeric|min:0',
            'barang_id'     => 'required',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);
    
        // Temukan data barang masuk yang akan diupdate
        $barangMasuk = BarangMasuk::findOrFail($id);
    
        // Hitung perbedaan antara stok yang baru dan yang sebelumnya
        $difference = $request->qty_masuk - $barangMasuk->qty_masuk;
    
        // Simpan perubahan data barang masuk ke database
        $barangMasuk->tgl_masuk = $request->tgl_masuk;
        $barangMasuk->qty_masuk = $request->qty_masuk;
        $barangMasuk->save();
    
        // Update stok barang
        $barang = Barang::find($request->barang_id);
    
        if ($difference > 0) {
            // Jika perbedaan positif, tambahkan ke stok
            $barang->stok += $difference;
        } elseif ($difference < 0) {
            // Jika perbedaan negatif, kurangi dari stok
            $barang->stok -= abs($difference);
        }

        // $barangMasuk = $barang->barangMasuk()->latest()->first();
        // if ($barangMasuk && $request->tgl_keluar < $barangMasuk->tgl_masuk) {
        //     return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal keluar tidak boleh sebelum tanggal masuk barang!']);
        // }
    
        // $barang->save();

        // session()->flash('success', 'Data barang masuk berhasil diperbarui');
    
        return redirect()->route('barangmasuk.index')->with('success', 'Barang masuk berhasil diperbarui');

    }
    
    
    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
    
        // Check if there are related barang keluar
        $relatedBarangKeluar = BarangKeluar::where('barang_id', $barangMasuk->barang_id)->exists();
        if ($relatedBarangKeluar) {
            return redirect()->route('barangmasuk.index')->with(['error' => 'Tidak dapat menghapus barang masuk yang telah memiliki barang keluar terkait!']);
        }
    
        // If there are no related barang keluar, proceed with deletion
        $barangMasuk->delete();
    
        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    

}