<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = ['type','brand','name','slug','year','base_price','image','description','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function builds(): HasMany { return $this->hasMany(VehicleBuild::class); }
}
