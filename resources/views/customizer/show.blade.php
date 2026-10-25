@extends('layouts.app')

@section('title', 'Custom '.$vehicle->brand.' '.$vehicle->name)
@section('nofooter', true)

@section('content')
@php
    $isRealPhotoDemo = $vehicle->slug === 'honda-civic-turbo';
@endphp
<form method="post" action="{{ route('customizer.store', $vehicle) }}" class="customizer" data-customizer data-base="{{ $vehicle->base_price }}">
    @csrf
    <div class="preview-panel">
        <div class="preview-top">
            <div>
                <span class="badge badge-{{ $vehicle->type }}">{{ $vehicle->type }}</span>
                @if($isRealPhotoDemo)<span class="badge badge-real">● Foto real beta</span>@endif
                <h2 style="font-family:Syne;margin:10px 0 0">{{ $vehicle->brand }} {{ $vehicle->name }}</h2>
                <span class="muted">{{ $vehicle->year }}</span>
            </div>
            <a class="btn btn-light btn-sm" href="{{ route('vehicles.index') }}">← Ganti kendaraan</a>
        </div>

        <div class="preview-stage {{ $isRealPhotoDemo ? 'preview-stage-real' : '' }}">
            @if($isRealPhotoDemo)
                <div class="real-photo-stack" data-real-vehicle>
                    <img data-color-layer class="real-vehicle-photo real-base-photo is-visible" src="{{ asset('images/demo/civic-stock.jpg') }}" alt="Honda Civic Type R dengan modifikasi Mugen">
                    <img data-color-layer class="real-vehicle-photo real-base-photo" src="{{ asset('images/demo/civic-stock.jpg') }}" alt="" aria-hidden="true">
                    <img data-mod-layer="livery" class="real-vehicle-photo real-mod-photo mod-livery" alt="" aria-hidden="true">
                    <img data-mod-layer="body" class="real-vehicle-photo real-mod-photo mod-body" alt="" aria-hidden="true">
                    <img data-mod-layer="hood" class="real-vehicle-photo real-mod-photo mod-hood" alt="" aria-hidden="true">
                    <img data-mod-layer="wheel" class="real-vehicle-photo real-mod-photo mod-wheel" alt="" aria-hidden="true">
                </div>
            @else
                <x-vehicle-art :type="$vehicle->type" color="#ff5a1f"/>
            @endif
        </div>
        <div class="preview-note">
            @if($isRealPhotoDemo)
                Mode uji foto nyata. Gambar referensi digunakan sementara dan belum untuk penggunaan komersial.
            @else
                Preview visual bersifat ilustrasi. Warna dan bentuk aktual dapat berbeda.
            @endif
        </div>
    </div>

    <aside class="config-panel">
        <div class="eyebrow">Studio konfigurasi</div>
        <h1>Racik kendaraanmu</h1>
        <p class="muted">Pilih satu opsi dari setiap kategori.</p>
        @if($errors->any())<div class="error-box">{{ $errors->first() }}</div>@endif

        <div class="form-group">
            <label>Nama build</label>
            <input class="input" name="name" value="{{ old('name', $vehicle->name.' Street Build') }}" required>
        </div>

        @foreach($categories as $category)
            <section class="category">
                <h3>{{ $category->icon }} {{ $category->name }}</h3>
                <div class="option-list">
                    @foreach($category->items as $item)
                        @php
                            $photo = null;
                            if ($isRealPhotoDemo && $item->preview_key === 'color') {
                                $photo = asset('images/demo/civic-stock.jpg');
                            } elseif ($isRealPhotoDemo && $item->preview_key === 'wheel') {
                                $photo = match(strtolower((string) $item->color)) {
                                    '#17181c' => asset('images/demo/civic-overlay-wheel-black.png'),
                                    '#9b6b32' => asset('images/demo/civic-overlay-wheel-bronze.png'),
                                    default => null,
                                };
                            } elseif ($isRealPhotoDemo && $item->preview_key === 'bodykit') {
                                $photo = asset('images/demo/civic-overlay-bodykit.png');
                            } elseif ($isRealPhotoDemo && $item->preview_key === 'hood') {
                                $photo = str_contains(strtolower($item->name), 'carbon')
                                    ? asset('images/demo/civic-overlay-carbon-hood.png')
                                    : null;
                            } elseif ($isRealPhotoDemo && $item->preview_key === 'livery') {
                                $photo = str_contains(strtolower($item->name), 'clean')
                                    ? null
                                    : asset('images/demo/civic-overlay-racing-livery.png');
                            }

                            $colorFilter = match (strtolower((string) $item->color)) {
                                '#3267d6' => 'hue-rotate(220deg) saturate(1.1)',
                                '#f3f3ef', '#ffffff', '#f5f5f5' => 'grayscale(1) brightness(1.38) contrast(.72)',
                                default => 'none',
                            };

                            $visualLayer = match ($item->preview_key) {
                                'bodykit', 'spoiler' => 'body',
                                'wheel', 'stance', 'hood', 'livery', 'color' => $item->preview_key,
                                default => null,
                            };
                            $visualActive = match ($item->preview_key) {
                                'wheel' => ! str_contains(strtolower($item->name), 'silver'),
                                'bodykit' => true,
                                'spoiler' => false,
                                'stance' => ! str_contains(strtolower($item->name), 'standard'),
                                'hood' => str_contains(strtolower($item->name), 'carbon'),
                                'livery' => ! str_contains(strtolower($item->name), 'clean'),
                                'color' => true,
                                default => false,
                            };
                        @endphp
                        <div class="option">
                            <input type="radio" name="items[{{ $category->id }}]" id="item-{{ $item->id }}" value="{{ $item->id }}"
                                data-price="{{ $item->price }}" data-kind="{{ $item->preview_key }}" data-color="{{ $item->color }}"
                                @if($photo) data-image="{{ $photo }}" @endif @if($visualLayer) data-layer="{{ $visualLayer }}" @endif
                                @if($item->preview_key === 'color') data-color-filter="{{ $colorFilter }}" @endif
                                data-visual-active="{{ $visualActive ? '1' : '0' }}" @checked($loop->first)>
                            <label for="item-{{ $item->id }}">
                                <span class="swatch" @if($item->color) style="background:{{ $item->color }};color:{{ in_array(strtolower($item->color), ['#ffffff','#f5f5f5']) ? '#111' : '#fff' }}" @endif>
                                    {{ !$item->color ? substr($item->name, 0, 1) : '' }}
                                </span>
                                <span><span class="option-name">{{ $item->name }}</span><span class="option-price">{{ $item->description }}</span></span>
                                <span class="option-price">+ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="summary">
            <div class="summary-row"><span>Total estimasi</span><strong data-total>Rp {{ number_format($vehicle->base_price, 0, ',', '.') }}</strong></div>
            @auth
                <button class="btn btn-orange" style="width:100%;margin-top:14px">Simpan ke garasi</button>
            @else
                <a class="btn btn-orange" style="width:100%;margin-top:14px" href="{{ route('login') }}">Masuk untuk menyimpan</a>
            @endauth
        </div>
    </aside>
</form>
@endsection
