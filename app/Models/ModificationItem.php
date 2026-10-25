<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModificationItem extends Model
{
    protected $fillable = ['category_id','vehicle_type','name','slug','price','color','preview_key','description','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function category(): BelongsTo { return $this->belongsTo(ModificationCategory::class, 'category_id'); }
}
