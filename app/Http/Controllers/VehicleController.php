<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();
        $vehicles = Vehicle::query()->where('is_active', true)
            ->when(in_array($type, ['mobil','motor']), fn ($q) => $q->where('type', $type))
            ->orderBy('type')->orderBy('brand')->get();
        return view('vehicles.index', compact('vehicles','type'));
    }
}
