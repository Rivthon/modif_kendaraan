<?php
namespace App\Http\Controllers;

use App\Models\ModificationCategory;
use App\Models\ModificationItem;
use App\Models\Vehicle;
use App\Models\VehicleBuild;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomizerController extends Controller
{
    public function show(Vehicle $vehicle): View
    {
        abort_unless($vehicle->is_active, 404);
        $categories = ModificationCategory::with(['items' => fn ($q) => $q
            ->where('is_active', true)->whereIn('vehicle_type', [$vehicle->type, 'semua'])->orderBy('price')])
            ->orderBy('sort_order')->get()->filter(fn ($category) => $category->items->isNotEmpty());
        return view('customizer.show', compact('vehicle','categories'));
    }

    public function store(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'items' => ['nullable','array'],
            'items.*' => ['integer','exists:modification_items,id'],
        ]);
        $items = ModificationItem::whereIn('id', $data['items'] ?? [])->where('is_active', true)
            ->whereIn('vehicle_type', [$vehicle->type, 'semua'])->get();
        $build = VehicleBuild::create([
            'user_id' => $request->user()->id,
            'vehicle_id' => $vehicle->id,
            'name' => $data['name'],
            'total_price' => $vehicle->base_price + $items->sum('price'),
        ]);
        $build->items()->attach($items->mapWithKeys(fn ($item) => [$item->id => ['price' => $item->price]]));
        return redirect()->route('garage.show', $build)->with('success', 'Build berhasil disimpan ke garasi.');
    }
}
