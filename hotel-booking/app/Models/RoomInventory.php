<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomInventory extends Model
{
    // Disables default incrementing IDs because we used a custom composite primary key
    public $incrementing = false;
    protected $primaryKey = ['room_type_id', 'inventory_date'];
    
    // Disables Laravel's default created_at/updated_at rows for this specific tracking table
    public $timestamps = false; 

    protected $fillable = ['room_type_id', 'inventory_date', 'available_count', 'price_override'];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
}
