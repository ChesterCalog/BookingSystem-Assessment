<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'room_type_id',
        'check_in',
        'check_out',
        'total_price',
        'status',
    ];

    protected $casts = [
        'check_in'    => 'date',
        'check_out'   => 'date',
        'total_price' => 'decimal:2',
    ];

    /**
     * The user who made this booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The room type for this booking.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Generate a formatted booking ID string (e.g. BK-2401).
     */
    public function getBookingCodeAttribute(): string
    {
        return 'BK-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
