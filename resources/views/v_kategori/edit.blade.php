@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('kategori.update', $rsetkategori->id) }}" method="POST" enctype="multipart/form-data">                    
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">Deskripsi</label>
                                <input type="text" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" value="{{ $rsetkategori->deskripsi }}" placeholder="Masukkan Kategori barang">
                            
                                <!-- error message untuk deskripsi -->
                                @error('merk')
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
                                      M
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kategori" id="Kategori" value="A">
                                    <label class="form-check-label" for="kategori">
                                      A
                                    </label>
                                  </div>       
                                  <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="kategori" value="BHP">
                                        <label class="form-check-label" for="kategori">
                                            BHP
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="kategori" value="BTHP">
                                        <label class="form-check-label" for="kategori">
                                            BTHP
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