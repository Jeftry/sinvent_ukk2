<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View; // Import the correct View class

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : View
    {
        $search = $request->input('search');

        // Ambil semua kategori menggunakan metode getKategoriAll dengan pencarian
        $rsetkategori = Kategori::select('kategori.id', 'deskripsi', 'kategori', DB::raw('ketKategori(kategori.kategori) as ketKategori'))
            ->when($search, function ($query, $search) {
                return $query->where('deskripsi', 'like', '%' . $search . '%')
                    ->orWhere(DB::raw('ketKategori(kategori.kategori) COLLATE utf8mb4_unicode_ci'), 'like', '%' . $search . '%')
                    ->orWhere('kategori.deskripsi', $search)
                    ->orWhere('kategori', 'like', '%' . $search . '%')

                    ->orWhere('kategori.id', $search); // Menambahkan pencarian berdasarkan ID+
            })

            //$rsetkategori->appends(['search'=>$search]);
            //->latest()
            ->paginate(3);
            $rsetkategori->appends(['search'=>$search]);

        return view('v_kategori.index', compact('rsetkategori', 'search'));
    }

    

    

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('v_kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        // $request->validate([
        //     'deskripsi' => 'required|unique:kategori',
        //     'kategori' => 'required',
        // ], [
        //     'deskripsi.required' => 'Deskripsi kategori harus diisi.',
        //     'deskripsi.unique' => 'Kategori sudah ada.',
        //     'kategori.required' => 'Kategori harus diisi.',
        // ]);

        // // Buat kategori baru
        // Kategori::create([
        //     'deskripsi' => $request->deskripsi,
        //     'kategori' => $request->kategori,
        //     'ketKategori' => $request->ketKategori // Sesuaikan dengan field yang ada di tabel kategori
        // ]);
        try {
            DB::beginTransaction(); // <= Starting the transaction
            // Insert a new order history
            DB::table('kategori')->insert([
                'order_id' => $orderID,
                'status' => 'pending',
            ]);
        
            DB::commit(); // <= Commit the changes
        } catch (\Exception $e) {
            report($e);
            
            DB::rollBack(); // <= Rollback in case of an exception
        }

        $existingKategori = Kategori::where('deskripsi', $request->deskripsi)->first();

        if ($existingKategori) {
            // Jika kategori dengan nama yang sama sudah ada, kembalikan error
            return redirect()->route('kategori.create')->withErrors([
                'error' => 'Kategori dengan nama yang sama sudah ada.',
            ])->withInput();
        }

        
        Kategori::create([
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);


        // Redirect ke halaman index kategori dengan pesan sukses
        return redirect()->route('kategori.index')->with('success', 'Data Berhasil Disimpan!');
    }

    

    /**
     * Display the specified resource.
     */
    public function show(string $id) : View
    {
        $rsetkategori = Kategori::findOrFail($id);
        

        // Return view
        return view('v_kategori.show', compact('rsetkategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) : View
    {
        $rsetkategori = Kategori::findOrFail($id);
        return view('v_kategori.edit', compact('rsetkategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori' => 'required',
            'deskripsi' => 'required'
        ]);

        $rsetkategori = Kategori::findOrFail($id);

        // Update category
        $rsetkategori->update([
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi
        ]);

        // Redirect to index
        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (DB::table('barang')->where('kategori_id', $id)->exists()) {
            return redirect()->route('kategori.index')->with(['error' => 'Data Gagal Dihapus karena terkait tabel barang!']);
        } else {
            $rsetKategori = Kategori::findOrFail($id);
            $rsetKategori->delete();
            return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }
    }

    function updateAPIKategori(Request $request, $kategori_id){
        $kategori = Kategori::find($kategori_id);

        if (null == $kategori){
            return response()->json(['status'=>"kategori tidak ditemukan"]);
        }

         $kategori->deskripsi= $request->deskripsi;
         $kategori->kategori = $request->kategori;
         $kategori->save();

        return response()->json(["status"=>"kategori berhasil diubah"]);
    }

    // function untuk membuat index api
    function showAPIKategori(Request $request){
        $kategori = Kategori::all();
        return response()->json($kategori);
    }

    // function untuk create api
    function buatAPIKategori(Request $request){
        $request->validate([
            'deskripsi' => 'required|string|max:100',
            'kategori' => 'required|in:M,A,BHP,BTHP',
        ]);

        // Simpan data kategori
        $kat = Kategori::create([
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);

        return response()->json(["status"=>"data berhasil dibuat"]);
    }

    // function untuk delete api
    function hapusAPIKategori($kategori_id){

        $del_kategori = Kategori::findOrFail($kategori_id);
        $del_kategori -> delete();

        return response()->json(["status"=>"data berhasil dihapus"]);
    }
}