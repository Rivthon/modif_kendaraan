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
                @if($isRealPhotoDemo)<span class="badge badge-real">● Studio 3D</span>@endif
                <h2 style="font-family:Syne;margin:10px 0 0">{{ $vehicle->brand }} {{ $vehicle->name }}</h2>
                <span class="muted">{{ $vehicle->year }}</span>
            </div>
            <a class="btn btn-light btn-sm" href="{{ route('vehicles.index') }}">← Ganti kendaraan</a>
        </div>

        <div class="preview-stage {{ $isRealPhotoDemo ? 'preview-stage-real' : '' }}">
            @if($isRealPhotoDemo)
                <div class="vehicle-3d-stage" data-3d-vehicle aria-label="Preview kendaraan 3D interaktif"></div>
                <span class="orbit-hint">Geser untuk memutar · Scroll untuk zoom</span>
            @else
                <x-vehicle-art :type="$vehicle->type" color="#ff5a1f"/>
            @endif
        </div>
        <div class="preview-note">
            @if($isRealPhotoDemo)
                Preview 3D interaktif. Semua modifikasi aktif dapat digabungkan.
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
                                $photo = match(strtolower((string) $item->color)) {
                                    '#f3f3ef', '#ffffff', '#f5f5f5' => asset('images/demo/civic-wheels.jpg'),
                                    '#3267d6' => asset('images/demo/civic-bodykit.jpg'),
                                    default => asset('images/demo/civic-stock.jpg'),
                                };
                            } elseif ($isRealPhotoDemo && $item->preview_key === 'wheel') {
                                $photo = match(strtolower((string) $item->color)) {
                                    '#17181c' => asset('images/demo/civic-wheel-black.png'),
                                    '#9b6b32' => asset('images/demo/civic-wheel-bronze.png'),
                                    default => asset('images/demo/civic-stock.jpg'),
                                };
                            } elseif ($isRealPhotoDemo && $item->preview_key === 'bodykit') {
                                $photo = asset('images/demo/civic-widebody.png');
                            } elseif ($isRealPhotoDemo && $item->preview_key === 'spoiler') {
                                $photo = asset('images/demo/civic-stock.jpg');
                            } elseif ($isRealPhotoDemo && $item->preview_key === 'stance') {
                                $photo = str_contains(strtolower($item->name), 'standard')
                                    ? asset('images/demo/civic-stock.jpg')
                                    : asset('images/demo/civic-stance-low.png');
                            } elseif ($isRealPhotoDemo && $item->preview_key === 'hood') {
                                $photo = str_contains(strtolower($item->name), 'carbon')
                                    ? asset('images/demo/civic-carbon-hood.png')
                                    : asset('images/demo/civic-stock.jpg');
                            } elseif ($isRealPhotoDemo && $item->preview_key === 'livery') {
                                $photo = str_contains(strtolower($item->name), 'clean')
                                    ? asset('images/demo/civic-stock.jpg')
                                    : asset('images/demo/civic-racing-livery.png');
                            }

                            $colorFilter = 'none';
                            $componentFilter = match (strtolower((string) $item->color)) {
                                '#17181c' => 'grayscale(1) brightness(.46) contrast(1.45)',
                                '#9b6b32' => 'sepia(1) saturate(1.9) hue-rotate(350deg) brightness(.78)',
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
                                @if($item->preview_key === 'wheel') data-component-filter="{{ $componentFilter }}" @endif
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
