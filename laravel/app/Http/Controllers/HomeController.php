<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the landing page with featured rooms.
     */
    public function index()
    {
        $featuredRooms = Room::available()->take(6)->get();

        $testimonials = [
            ['name' => 'Maria Santos',   'rating' => 5, 'comment' => 'Amazing experience! The VIP room was top-notch. Sound quality was excellent and staff were so friendly.'],
            ['name' => 'James Reyes',    'rating' => 5, 'comment' => 'Great place for parties! Booked the Party Room for my birthday and everyone had a blast. Will definitely return!'],
            ['name' => 'Ana Cruz',       'rating' => 4, 'comment' => 'Clean, well-maintained rooms with awesome song selections. Prices are very reasonable too!'],
            ['name' => 'Carlo Mendoza',  'rating' => 5, 'comment' => 'The online booking system is super convenient. Room was ready when we arrived. Loved it!'],
        ];

        $faqs = [
            ['q' => 'How do I make a reservation?',           'a' => 'You can book online via our website or walk in. Online booking is recommended to secure your preferred time slot.'],
            ['q' => 'Can I book without an account?',          'a' => 'Yes! We offer guest bookings. Just fill in your name, email, and phone number to complete your reservation.'],
            ['q' => 'What is your cancellation policy?',       'a' => 'Cancellations made at least 24 hours before your booking are free of charge. Late cancellations may incur a fee.'],
            ['q' => 'Do you provide food and drinks?',         'a' => 'Yes, we offer snacks and beverages that can be added to your booking. Outside food is also welcome.'],
            ['q' => 'How many people can fit in each room?',   'a' => 'Our rooms range from small (2–6 pax) to XL Party rooms (up to 30 pax). Check our Rooms page for details.'],
            ['q' => 'Is there a minimum booking time?',        'a' => 'The minimum booking duration is 1 hour. You can extend in 30-minute increments if the room is available.'],
        ];

        return view('home', compact('featuredRooms', 'testimonials', 'faqs'));
    }
}
