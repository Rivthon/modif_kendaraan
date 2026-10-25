<?php
namespace App\Http\Controllers;

use App\Models\VehicleBuild;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GarageController extends Controller
{
    public function index(Request $request): View
    {
        $builds = $request->user()->builds()->with(['vehicle','items.category'])->latest()->get();
        return view('garage.index', compact('builds'));
    }
    public function show(Request $request, VehicleBuild $build): View
    {
        abort_unless($build->user_id === $request->user()->id || $request->user()->is_admin, 403);
        $build->load(['vehicle','items.category']);
        return view('garage.show', compact('build'));
    }
    public function destroy(Request $request, VehicleBuild $build): RedirectResponse
    {
        abort_unless($build->user_id === $request->user()->id, 403);
        $build->delete();
        return redirect()->route('garage.index')->with('success', 'Build telah dihapus.');
    }
}
