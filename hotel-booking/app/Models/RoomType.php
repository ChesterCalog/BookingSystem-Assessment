<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomType extends Model
{
    use HasFactory;
    // Allows Laravel to insert data into these columns automatically
    protected $fillable = ['name', 'base_price', 'total_inventory'];

    // Relates RoomType to RoomInventory (One-to-Many)
    public function inventories(): HasMany
    {
        return $this->hasMany(RoomInventory::class);
    }

    public function roomUnits(): HasMany
    {
        return $this->hasMany(RoomUnit::class);
    }

    // Relates RoomType to Bookings (One-to-Many)
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}