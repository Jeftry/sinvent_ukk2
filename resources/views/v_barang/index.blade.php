@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
        <div class="pull-left">
            <h4 class="font-weight-bold mb-3">DAFTAR BARANG</h4>
        </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('barang.create') }}" class="btn btn-md btn-success mb-3">TAMBAH BARANG</a>
                        @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('barang.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari Barang..." value="{{ request()->input('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                                @if(request()->filled('search'))
                                    <div class="input-group-append">
                                    <a href="{{ route('barang.index') }}" class="btn btn-secondary"><i class="fa fa-times"></i></a>
                                    </div>
                                    @endif
                                </div>
                            </form>
                    </div>
                </div>
                


                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>MERK</th>
                            <th>SERI</th>
                            <th>SPESIFIKASI</th>
                            <th>KATEGORI</th>
                            <th>KATERANGAN</th>
                            <th>STOK</th>
                            <th style="width: 15%">AKSI</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rsetBarang as $index => $rowbarang)
                            <tr>
                                <td>{{ ++$index }}</td>
                                <td>{{ $rowbarang->merk }}</td>
                                <td>{{ $rowbarang->seri  }}</td>
                                <td>{{ $rowbarang->spesifikasi  }}</td>
                                <td>{{ $rowbarang->kategori->deskripsi }}</td>
                                <td>{{ $rowbarang->kategori->kategori }}</td>

                                <td>{{ $rowbarang->stok  }}</td>
                                <!-- <td>{{ $rowbarang->foto  }}</td> -->
                                <!-- <td class="text-center">
                                    <img src="{{ asset('storage/foto/'.$rowbarang->foto) }}" class="rounded" style="width: 150px">
                                </td> -->
                                <td class="text-center"> 
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('barang.destroy', $rowbarang->id) }}" method="POST">
                                        <a href="{{ route('barang.show', $rowbarang->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('barang.edit', $rowbarang->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" ><i class="fa fa-trash"></i></button>

                                    </form>
                                </td>
                            </tr>
                        @empty
                            <div class="alert">
                                Data barang belum tersedia
                            </div>
                        @endforelse
                    </tbody>
                    
                </table>

                    {{ $rsetBarang->links() }}
                    
            </div>
        </div>
    </div>
@endsection