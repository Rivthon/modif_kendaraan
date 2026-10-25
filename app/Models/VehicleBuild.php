<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VehicleBuild extends Model
{
    protected $table = 'builds';
    protected $fillable = ['user_id','vehicle_id','name','total_price','status'];
    public function vehicle(): BelongsTo { return $this->belongsTo(Vehicle::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): BelongsToMany { return $this->belongsToMany(ModificationItem::class, 'build_items', 'build_id')->withPivot('price')->withTimestamps(); }
}
