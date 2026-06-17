<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'booking_date',
        'start_time',
        'end_time',
        'num_guests',
        'total_cost',
        'status',
        'special_requests',
        'reference_number',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_cost'   => 'decimal:2',
    ];

    // Auto-generate reference number on creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->reference_number = 'BK-' . strtoupper(Str::random(8));
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id')
                    ->where('booking_type', 'booking');
    }

    // Calculate total cost based on hours and price per hour
    public static function calculateCost(Room $room, string $startTime, string $endTime): float
    {
        $start = \Carbon\Carbon::parse($startTime);
        $end   = \Carbon\Carbon::parse($endTime);
        $hours = $end->diffInMinutes($start) / 60;
        return round($hours * $room->price_per_hour, 2);
    }

    // Status badge color helper
    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'approved'  => 'success',
            'pending'   => 'warning',
            'rejected'  => 'danger',
            'completed' => 'info',
            'cancelled' => 'secondary',
            default     => 'secondary',
        };
    }
}
