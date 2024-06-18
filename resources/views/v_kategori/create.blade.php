@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('kategori.store') }}" method="POST" enctype="multipart/form-data">                    
                            @csrf

                            <div class="form-group">
                                <label class="font-weight-bold">Kategori</label>
                                <input type="text" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" value="{{ old('deskripsi') }}" placeholder="Masukkan kategori barang">

                                <!-- error message untuk deskripsi -->
                                @error('deskripsi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                           <div class="form-group">
                                <label class="font-weight-bold">Jenis</label>
                         
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kategori" id="kategori" value="M">
                                    <label class="form-check-label" for="kategori">
                                      Modal
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kategori" id="Kategori" value="A">
                                    <label class="form-check-label" for="kategori">
                                      Alat
                                    </label>
                                  </div>       
                                  <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="kategori" value="BHP">
                                        <label class="form-check-label" for="kategori">
                                            Bahan Habis Pakai
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="kategori" value="BTHP">
                                        <label class="form-check-label" for="kategori">
                                            Bahan Tidak Habis Pakai
                                        </label>
                                    </div>                         

                                <!-- error message untuk spesifikasi -->
                                @error('kategori')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                                                        
                            <button type="submit" class="btn btn-md btn-success">SIMPAN</button>
                            <button type="reset" class="btn btn-md btn-danger">RESET</button>
                            <a href="{{ route('kategori.index') }}" class="btn btn-md btn-primary">KEMBALI</a>

                        </form> 
                    </div>
                </div>

 

            </div>
        </div>
    </div>
@endsection