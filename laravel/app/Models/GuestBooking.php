<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GuestBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
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
            $booking->reference_number = 'GB-' . strtoupper(Str::random(8));
        });
    }

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id')
                    ->where('booking_type', 'guest_booking');
    }

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
