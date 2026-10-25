<?php
namespace App\Http\Controllers;

use App\Models\ModificationCategory;
use App\Models\ModificationItem;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleBuild;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    private function authorizeAdmin(Request $request): void { abort_unless($request->user()?->is_admin, 403); }
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);
        return view('admin.index', [
            'vehicles' => Vehicle::latest()->get(), 'categories' => ModificationCategory::orderBy('sort_order')->get(),
            'items' => ModificationItem::with('category')->latest()->get(),
            'stats' => ['vehicles' => Vehicle::count(), 'items' => ModificationItem::count(), 'users' => User::count(), 'builds' => VehicleBuild::count()],
        ]);
    }
    public function vehicle(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $data = $request->validate(['type'=>'required|in:mobil,motor','brand'=>'required|max:80','name'=>'required|max:100','year'=>'required|integer|min:1950|max:2100','base_price'=>'required|integer|min:0','description'=>'nullable|max:500']);
        $data['slug'] = Str::slug($data['brand'].'-'.$data['name'].'-'.Str::random(5));
        Vehicle::create($data);
        return back()->with('success', 'Kendaraan berhasil ditambahkan.');
    }
    public function item(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $data = $request->validate(['category_id'=>'required|exists:modification_categories,id','vehicle_type'=>'required|in:mobil,motor,semua','name'=>'required|max:100','price'=>'required|integer|min:0','color'=>'nullable|max:20','preview_key'=>'nullable|max:50','description'=>'nullable|max:500']);
        $data['slug'] = Str::slug($data['name'].'-'.Str::random(5));
        ModificationItem::create($data);
        return back()->with('success', 'Komponen berhasil ditambahkan.');
    }
    public function deleteVehicle(Request $request, Vehicle $vehicle): RedirectResponse { $this->authorizeAdmin($request); $vehicle->delete(); return back()->with('success','Kendaraan dihapus.'); }
    public function deleteItem(Request $request, ModificationItem $item): RedirectResponse { $this->authorizeAdmin($request); $item->delete(); return back()->with('success','Komponen dihapus.'); }
}
