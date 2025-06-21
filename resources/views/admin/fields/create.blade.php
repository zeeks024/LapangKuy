@extends('layouts.app')

@section('title', 'Tambah Lapangan - Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tambah Lapangan Baru</h1>
        <a href="{{ route('admin.fields') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.fields.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lapangan</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" required>
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Harga per Jam</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Buka (Jam)</label>
                        <input type="time" name="open_time" class="form-control @error('open_time') is-invalid @enderror" value="{{ old('open_time') }}" required>
                        @error('open_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tutup (Jam)</label>
                        <input type="time" name="close_time" class="form-control @error('close_time') is-invalid @enderror" value="{{ old('close_time') }}" required>
                        @error('close_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="futsal" {{ old('category')=='futsal'?'selected':'' }}>Futsal</option>
                        <option value="basket" {{ old('category')=='basket'?'selected':'' }}>Basket</option>
                        <option value="badminton" {{ old('category')=='badminton'?'selected':'' }}>Badminton</option>
                        <option value="tennis" {{ old('category')=='tennis'?'selected':'' }}>Tennis</option>
                        <option value="voli" {{ old('category')=='voli'?'selected':'' }}>Voli</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>                <div class="mb-3">
                    <x-file-upload 
                        name="image" 
                        label="Foto Lapangan" 
                        help="Upload foto lapangan (JPG, PNG, max 2MB)" 
                        accept="image/jpeg,image/png" 
                        :currentImage="old('image_url')"
                    />
                    
                    <div class="mt-3">
                        <label class="form-label">Atau Masukkan URL Gambar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                            <input type="url" name="image_url" class="form-control @error('image_url') is-invalid @enderror" 
                                value="{{ old('image_url') }}" placeholder="https://example.com/gambar-lapangan.jpg">
                            @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <small class="form-text text-muted">Masukkan URL gambar online jika tidak mengupload file</small>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="promo" id="promo" class="form-check-input" {{ old('promo')?'checked':'' }}>
                    <label for="promo" class="form-check-label">Promo</label>
                </div>

                <button type="submit" class="btn btn-success">Simpan Lapangan</button>
            </form>
        </div>
    </div>
</div>
@endsection
