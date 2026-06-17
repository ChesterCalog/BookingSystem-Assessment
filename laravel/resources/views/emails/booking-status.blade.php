<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Status Update</title>
<style>
  body     { margin:0; padding:0; background:#f1f5f9; font-family:'Helvetica Neue',Arial,sans-serif; }
  .wrapper { max-width:580px; margin:32px auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
  .header  { padding:36px 32px; text-align:center; }
  .header.approved { background:linear-gradient(135deg,#059669,#047857); }
  .header.rejected { background:linear-gradient(135deg,#dc2626,#b91c1c); }
  .header.completed{ background:linear-gradient(135deg,#2563eb,#1d4ed8); }
  .header h1{ color:#fff; margin:0 0 4px; font-size:24px; }
  .header p { color:rgba(255,255,255,.8); margin:0; font-size:14px; }
  .body    { padding:32px; }
  table.details { width:100%; border-collapse:collapse; margin-bottom:24px; }
  table.details td { padding:10px 0; border-bottom:1px solid #f1f5f9; font-size:14px; }
  table.details td:first-child { color:#64748b; width:40%; }
  table.details td:last-child  { color:#1e293b; font-weight:500; }
  .footer  { background:#f8fafc; padding:24px 32px; text-align:center; border-top:1px solid #e2e8f0; }
  .footer p{ color:#94a3b8; font-size:12px; margin:4px 0; }
</style>
</head>
<body>
<div class="wrapper">

  <div class="header {{ $newStatus }}">
    @if($newStatus === 'approved')
      <p style="font-size:40px;margin:0 0 8px;">🎉</p>
      <h1>Booking Approved!</h1>
      <p>Get ready to sing your heart out!</p>
    @elseif($newStatus === 'rejected')
      <p style="font-size:40px;margin:0 0 8px;">😔</p>
      <h1>Booking Not Approved</h1>
      <p>We're sorry, but we could not confirm this booking.</p>
    @elseif($newStatus === 'completed')
      <p style="font-size:40px;margin:0 0 8px;">⭐</p>
      <h1>Thank You for Your Visit!</h1>
      <p>We hope you had an amazing time!</p>
    @endif
  </div>

  <div class="body">
    <p style="font-size:16px;color:#1e293b;margin-top:0">
      Hi <strong>{{ isset($booking->user) ? $booking->user->name : ($booking->full_name ?? 'Valued Customer') }}</strong>,
    </p>

    @if($newStatus === 'approved')
    <p style="color:#64748b;font-size:14px;line-height:1.6">
      Great news! Your booking at <strong>KaraokeZone</strong> has been <strong style="color:#059669">approved</strong>. We look forward to seeing you!
    </p>
    @elseif($newStatus === 'rejected')
    <p style="color:#64748b;font-size:14px;line-height:1.6">
      Unfortunately, we were unable to approve your booking. This may be due to a scheduling conflict or room unavailability. Please <a href="#" style="color:#7c3aed">book a different time</a> or contact us directly.
    </p>
    @elseif($newStatus === 'completed')
    <p style="color:#64748b;font-size:14px;line-height:1.6">
      Thank you for visiting KaraokeZone! We hope you had a fantastic time. We'd love to see you again soon!
    </p>
    @endif

    <table class="details">
      <tr><td>Reference</td><td style="font-family:monospace;font-weight:700;color:#7c3aed">{{ $booking->reference_number }}</td></tr>
      <tr><td>Room</td><td>{{ $booking->room->name }}</td></tr>
      <tr><td>Date</td><td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</td></tr>
      <tr><td>Time</td><td>{{ $booking->start_time }} – {{ $booking->end_time }}</td></tr>
      <tr><td>Total Cost</td><td style="font-size:20px;font-weight:800;color:#7c3aed">₱{{ number_format($booking->total_cost, 2) }}</td></tr>
    </table>

    @if($newStatus === 'approved')
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px;">
      <p style="margin:0 0 8px;font-weight:600;color:#166534;font-size:13px;">✅ Important reminders:</p>
      <ul style="margin:0;padding-left:18px;color:#334155;font-size:13px;line-height:1.8">
        <li>Please arrive 10 minutes before your scheduled time.</li>
        <li>Bring your reference number: <strong>{{ $booking->reference_number }}</strong></li>
        <li>Payment is due upon arrival. We accept cash, GCash, and cards.</li>
      </ul>
    </div>
    @endif
  </div>

  <div class="footer">
    <p>Questions? Email us at <strong>hello@karaokeZone.com</strong> or call <strong>+63 912 345 6789</strong></p>
    <p style="margin-top:12px;color:#cbd5e1;font-size:11px;">&copy; {{ date('Y') }} KaraokeZone. All rights reserved.</p>
  </div>
</div>
</body>
</html>
