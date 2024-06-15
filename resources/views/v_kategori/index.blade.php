@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="pull-left">
            <h4 class="font-weight-bold mb-3">DAFTAR KATEGORI</h4>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('kategori.create') }}" class="btn btn-md btn-success mb-3">TAMBAH KATEGORI</a>
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

                    <form action="{{ route('kategori.index') }}" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" placeholder="Cari kategori..." value="{{ request()->input('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                            @if(request()->filled('search'))
                            <div class="input-group-append">
                                <a href="{{ route('kategori.index') }}" class="btn btn-danger"><i class="fa fa-times"></i></a>
                            </div>
                            @endif
                        </div>
                    </form>


                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>DESKRIPSI</th>
                                <th>KATEGORI</th>
                                <th>KETERANGAN</th>
                                <th style="width: 15%">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rsetkategori as $index => $rk )
                            <tr>
                                <td>{{ $rk->id }}</td>
                                <td>{{ $rk->deskripsi }}</td>
                                <td>{{ $rk->kategori }}</td>
                                <td>{{ $rk->ketKategori }}</td>
                                <td class="text-center">
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('kategori.destroy', $rk->id) }}" method="POST">
                                        <a href="{{ route('kategori.show', $rk->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('kategori.edit', $rk->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Data Kategori belum tersedia</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $rsetkategori->links('pagination::bootstrap-5') }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection