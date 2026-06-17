<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'size',
        'capacity',
        'price_per_hour',
        'description',
        'amenities',
        'image',
        'is_available',
    ];

    protected $casts = [
        'amenities'    => 'array',
        'is_available' => 'boolean',
        'price_per_hour' => 'decimal:2',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function guestBookings()
    {
        return $this->hasMany(GuestBooking::class);
    }

    /**
     * Check if room is booked for a given date and time range.
     * Prevents double-booking.
     */
    public function isBookedFor(string $date, string $startTime, string $endTime, ?int $excludeId = null): bool
    {
        $userConflict = $this->bookings()
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();

        $guestConflict = $this->guestBookings()
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->exists();

        return $userConflict || $guestConflict;
    }

    // Scope: only available rooms
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Scope: filter by type
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Accessor: image URL
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/room-placeholder.jpg');
    }
}
