@extends('layouts.admin')

@section('title', 'Edit Sparepart')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Sparepart</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.spare-parts.update', $sparePart->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="name">Nama Sparepart</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $sparePart->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold text-gray-300">Kode SKU (Unik)</label>
                            <input type="text" name="sku"
                                class="form-control bg-secondary text-white cursor-not-allowed border-0"
                                value="{{ old('sku', $sparePart->sku) }}" readonly
                                title="SKU tidak dapat diubah demi integritas data">
                            <small class="text-muted">SKU bersifat permanen dan tidak dapat diubah.</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="brand">Brand (Opsional)</label>
                            <input type="text" name="brand" id="brand"
                                class="form-control @error('brand') is-invalid @enderror"
                                value="{{ old('brand', $sparePart->brand) }}">
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="stock">Stok</label>
                            <input type="number" name="stock" id="stock"
                                class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock', $sparePart->stock) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="cost_price">Harga Beli</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="cost_price" id="cost_price"
                                    class="form-control @error('cost_price') is-invalid @enderror"
                                    value="{{ old('cost_price', $sparePart->cost_price) }}" required>
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="sell_price">Harga Jual</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="sell_price" id="sell_price"
                                    class="form-control @error('sell_price') is-invalid @enderror"
                                    value="{{ old('sell_price', $sparePart->sell_price) }}" required>
                                @error('sell_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $sparePart->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Aktif
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection