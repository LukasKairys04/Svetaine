@extends('admin.layout')

@section('title', ($product->exists ? 'Redaguoti' : 'Naujas') . ' produktas')



@section('admin')

    @php $isNew = !$product->exists; @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h1 class="fw-bold m-0">{{ $isNew ? 'Naujas produktas' : 'Redaguoti: ' . $product->name }}</h1>

        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Atgal</a>

    </div>



    <form method="POST" action="{{ $isNew ? route('admin.products.store') : route('admin.products.update', $product) }}">

        @csrf

        @unless($isNew) @method('PUT') @endunless



        <div class="row g-3">

            <div class="col-lg-8">

                <div class="card shadow-sm mb-3">

                    <div class="card-body">

                        <h5 class="mb-3">Pagrindiniai</h5>

                        <div class="row g-3">

                            <div class="col-md-8">

                                <label class="form-label small">Pavadinimas *</label>

                                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" required>

                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror

                            </div>

                            <div class="col-md-4">

                                <label class="form-label small">Kategorija *</label>

                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>

                                    <option value="">— pasirinkti —</option>

                                    @foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>@endforeach

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label small">Slug (URL dalis)</label>

                                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="form-control" placeholder="automatinis pagal pavadinimą">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label small">Prekės ženklas</label>

                                <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="form-control">

                            </div>

                            <div class="col-12">

                                <label class="form-label small">Trumpas aprašymas</label>

                                <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="form-control" placeholder="Kas tai per produktas ir ką daro (1 sakinys).">

                            </div>

                            <div class="col-12">

                                <label class="form-label small">Pilnas aprašymas</label>

                                <textarea name="description" class="form-control" rows="6" placeholder="Trumpai ir paprastai: kas tai, kam skirtas, kokia nauda.">{{ old('description', $product->description) }}</textarea>

                            </div>

                            <div class="col-12">

                                <label class="form-label small">Paveikslėlio URL</label>

                                <input type="text" name="image" value="{{ old('image', $product->image) }}" class="form-control" placeholder="https://...">

                            </div>

                        </div>

                    </div>

                </div>



                <div class="card shadow-sm mb-3">

                    <div class="card-body">

                        <h5 class="mb-3">Mitybos informacija</h5>

                        <div class="row g-3">

                            <div class="col-md-4"><label class="form-label small">Porcijos dydis</label><input type="text" name="serving_size" value="{{ old('serving_size', $product->serving_size) }}" class="form-control"></div>

                            <div class="col-md-4"><label class="form-label small">Porcijų pakuotėje</label><input type="number" name="servings_per_container" value="{{ old('servings_per_container', $product->servings_per_container) }}" class="form-control"></div>

                            <div class="col-md-4"><label class="form-label small">Kalorijos (kcal)</label><input type="number" step="0.01" name="calories" value="{{ old('calories', $product->calories) }}" class="form-control"></div>

                            <div class="col-md-3"><label class="form-label small">Baltymai (g)</label><input type="number" step="0.01" name="protein" value="{{ old('protein', $product->protein) }}" class="form-control"></div>

                            <div class="col-md-3"><label class="form-label small">Angliavandeniai (g)</label><input type="number" step="0.01" name="carbs" value="{{ old('carbs', $product->carbs) }}" class="form-control"></div>

                            <div class="col-md-3"><label class="form-label small">Cukrus (g)</label><input type="number" step="0.01" name="sugar" value="{{ old('sugar', $product->sugar) }}" class="form-control"></div>

                            <div class="col-md-3"><label class="form-label small">Skaidulos (g)</label><input type="number" step="0.01" name="fiber" value="{{ old('fiber', $product->fiber) }}" class="form-control"></div>

                            <div class="col-md-3"><label class="form-label small">Riebalai (g)</label><input type="number" step="0.01" name="fat" value="{{ old('fat', $product->fat) }}" class="form-control"></div>

                            <div class="col-md-3"><label class="form-label small">Sotieji (g)</label><input type="number" step="0.01" name="saturated_fat" value="{{ old('saturated_fat', $product->saturated_fat) }}" class="form-control"></div>

                            <div class="col-md-3"><label class="form-label small">Natris (mg)</label><input type="number" step="0.01" name="sodium" value="{{ old('sodium', $product->sodium) }}" class="form-control"></div>

                        </div>

                    </div>

                </div>

            </div>



            <div class="col-lg-4">

                <div class="card shadow-sm mb-3">

                    <div class="card-body">

                        <h5 class="mb-3">Kaina ir sandėlis</h5>

                        <div class="mb-2"><label class="form-label small">Kaina (€) *</label><input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror" required>@error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>

                        <div class="mb-2"><label class="form-label small">Akcijos kaina (€)</label><input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="form-control @error('sale_price') is-invalid @enderror">@error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>

                        <div class="mb-2"><label class="form-label small">Likutis sandėlyje *</label><input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control" required></div>

                    </div>

                </div>



                <div class="card shadow-sm mb-3">

                    <div class="card-body">

                        <h5 class="mb-3">Reitingas</h5>

                        <div class="mb-2"><label class="form-label small">Reitingas (0–5)</label><input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $product->rating) }}" class="form-control"></div>

                        <div class="mb-2"><label class="form-label small">Atsiliepimų skaičius</label><input type="number" name="rating_count" value="{{ old('rating_count', $product->rating_count) }}" class="form-control"></div>

                    </div>

                </div>



                <div class="card shadow-sm mb-3">

                    <div class="card-body">

                        <h5 class="mb-3">Būsena</h5>

                        <div class="form-check form-switch mb-2"><input type="checkbox" name="featured" value="1" class="form-check-input" id="featured" @checked(old('featured', $product->featured))><label class="form-check-label" for="featured">Rekomenduojama</label></div>

                        <div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $product->is_active ?? true))><label class="form-check-label" for="is_active">Aktyvus</label></div>

                    </div>

                </div>



                <button class="btn btn-primary w-100 btn-lg"><i class="bi bi-check2"></i> {{ $isNew ? 'Sukurti' : 'Išsaugoti' }}</button>

            </div>

        </div>

    </form>

@endsection

