<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Barangmasuk;
use App\Models\Barangkeluar;
use Illuminate\View\View;
// use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : View
    {
        $search = $request->input('search');

        // Ambil semua kategori menggunakan metode getKategoriAll dengan pencarian
        $rsetBarang = Barang::with('kategori')
                            ->when($search, function ($query, $search) {
                            return $query->where('merk', 'like', '%' . $search . '%')
                                ->orWhere('seri', 'like', '%' . $search . '%')
                                ->orWhereHas('kategori', function ($query) use ($search) {
                                $query->where('deskripsi', 'like', '%' . $search . '%');
                                });
                        })
                        ->latest()
                        ->paginate(10);
    
                        return view('v_barang.index', compact('rsetBarang', 'search'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriOptions= Kategori::all();
        // return view('barang.create' compact('kategoriOptions'));
        return view('v_barang.create', compact('kategoriOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return $request;

       // dd("");
        //validate form
        $request->validate([
            'merk'    => 'required',
            'seri'     => 'required',
            'spesifikasi'     => 'required',
            'kategori_id'  => 'required',
            // 'kelas'   => 'required|not_in:blank',
            // 'rombel'  => 'required',
        //     'foto'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // //upload image
        // $foto = $request->file('foto');
        // $foto->storeAs('public/foto', $foto->hashName());

        //create post
        Barang::create([
            'merk'     => $request->merk,
            'seri'      => $request->seri,
            'spesifikasi'      => $request->spesifikasi,
            'kategori_id'   => $request->kategori_id
            // 'foto'     => $foto->hashName()
        ]);

        //redirect to index
        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarang = Barang::find($id);

        //return $Barang;

        //return view
        return view('v_barang.show', compact('rsetBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        // $akategori = array('blank'=>'Pilih Barang',
        //                             'M'=>'M - Makanan',
        //                             'A'=>'A - Angkutan',
        //                             'BHP'=>'BHP - Beras',
        //                             'BTHP'=>'BTHP - Batu'
        //                 );

        // $rsetBarang = Barang::find($id);
        // return view('barang.edit', compact('rsetBarang','akategori'));

        $kategoriOptions = Kategori::all();                    
        // return view('v_barangg.create',compact('kategoriOptions'));
        $rsetBarang = Barang::find($id);
        $selectedkategori = Kategori::find($rsetBarang->kategori_id); 
        //return $rsetBarang;
        return view('v_barang.edit', compact('rsetBarang','kategoriOptions', 'selectedkategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'merk'    => 'required',
            'seri'     => 'required',
            'spesifikasi'  => 'required',
            'kategori_id'  => 'required'
        ]);

        $rsetBarang = Barang::find($id);


        //update post without image
        $rsetBarang->update([
            'merk'     => $request->merk,
            'seri'      => $request->seri,
            'spesifikasi'   => $request->spesifikasi,
            'kategori_id'   => $request->kategori_id,
        ]);

        //redirect to index
        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Diubah!']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //$rsetBarang = Barang::find($id);

        $masukTerkait = Barangmasuk::where('barang_id', $id)->exists();

        $keluarTerkait = Barangkeluar::where('barang_id', $id)->exists();

        $stokBarang = Barang::where('id', $id)->where('stok','>',0)->exists();



        // Check if stok is greater than 0 before deleting
        if ($masukTerkait || $keluarTerkait || $stokBarang ) {
            return redirect()->route('barang.index')->with(['error' => 'Barang yang masih terkait atau stok lebih dari 0 tidak dapat dihapus! ']);
        }else{

            $rsetBarang = Barang::find($id);
            $rsetBarang->delete();
            return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Dihapus!']);

        }

        // if ($stokBarang ) {
        //     return redirect()->route('barang.index')->with(['error' => 'Barang stok lebih dari 0 tidak dapat dihapus!']);
        // }else{

        //     $rsetBarang = Barang::find($id);
        //     $rsetBarang->delete();
        //     return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Dihapus!']);

        // }
        // Delete post
        //$rsetBarang->delete();

        // Redirect to index
        //return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }


}