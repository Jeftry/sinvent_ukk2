@extends('layouts.main')

@section('content')
    <section class="hero py-5 bg-primary text-white">
        <div class="container text-center">
            <h1 class="font-weight-bold">SIJA SISTEM INVENTORY</h1>
        </div>
    </section>

    <hr>

    <section id="features" class="features py-5">
        <div class="container">
            <div class="row mt-4 d-flex justify-content-center">
                <div class="col-md-3 mb-4 d-flex">
                    <div class="card border-0 shadow rounded flex-fill d-flex flex-column text-center feature-card">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="fas fa-tags fa-3x text-primary mb-3"></i>
                            <h4 class="font-weight-bold">Kategori</h4>
                            <p class="flex-grow-1">Manage categories</p>
                            <a href="{{ route('kategori.index') }}" class="btn btn-outline-primary btn-sm mt-auto">View Categories</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4 d-flex">
                    <div class="card border-0 shadow rounded flex-fill d-flex flex-column text-center feature-card">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="fas fa-boxes fa-3x text-success mb-3"></i>
                            <h4 class="font-weight-bold">Barang</h4>
                            <p class="flex-grow-1">Manage items</p>
                            <a href="{{ route('barang.index') }}" class="btn btn-outline-success btn-sm mt-auto">View Items</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4 d-flex">
                    <div class="card border-0 shadow rounded flex-fill d-flex flex-column text-center feature-card">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="fas fa-arrow-down fa-3x text-info mb-3"></i>
                            <h4 class="font-weight-bold">Barang Masuk</h4>
                            <p class="flex-grow-1">Track incoming items</p>
                            <a href="{{ route('barangmasuk.index') }}" class="btn btn-outline-info btn-sm mt-auto">View Incoming Items</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4 d-flex">
                    <div class="card border-0 shadow rounded flex-fill d-flex flex-column text-center feature-card">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="fas fa-arrow-up fa-3x text-danger mb-3"></i>
                            <h4 class="font-weight-bold">Barang Keluar</h4>
                            <p class="flex-grow-1">Track outgoing items</p>
                            <a href="{{ route('barangkeluar.index') }}" class="btn btn-outline-danger btn-sm mt-auto">View Outgoing Items</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .feature-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection