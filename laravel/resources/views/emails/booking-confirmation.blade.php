<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Confirmation</title>
<style>
  body      { margin:0; padding:0; background:#f1f5f9; font-family:'Helvetica Neue',Arial,sans-serif; }
  .wrapper  { max-width:580px; margin:32px auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
  .header   { background: linear-gradient(135deg,#7c3aed,#5b21b6); padding:36px 32px; text-align:center; }
  .header h1{ color:#fff; margin:0 0 4px; font-size:26px; }
  .header p { color:#ddd6fe; margin:0; font-size:14px; }
  .body     { padding:32px; }
  .ref-box  { background:#f5f3ff; border:1px solid #ddd6fe; border-radius:8px; padding:16px; text-align:center; margin-bottom:24px; }
  .ref-box small { color:#7c3aed; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
  .ref-box span  { display:block; color:#4c1d95; font-size:28px; font-weight:800; font-family:monospace; margin-top:4px; }
  table.details { width:100%; border-collapse:collapse; margin-bottom:24px; }
  table.details td { padding:10px 0; border-bottom:1px solid #f1f5f9; font-size:14px; }
  table.details td:first-child { color:#64748b; width:40%; }
  table.details td:last-child  { color:#1e293b; font-weight:500; }
  .status-badge { display:inline-block; background:#fef3c7; color:#92400e; border:1px solid #fde68a; border-radius:20px; padding:4px 14px; font-size:12px; font-weight:600; }
  .cost { font-size:28px; font-weight:800; color:#7c3aed; }
  .footer { background:#f8fafc; padding:24px 32px; text-align:center; border-top:1px solid #e2e8f0; }
  .footer p { color:#94a3b8; font-size:12px; margin:4px 0; }
  .cta-btn  { display:inline-block; background:#7c3aed; color:#fff; text-decoration:none; padding:12px 28px; border-radius:8px; font-weight:600; font-size:14px; margin-top:8px; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <p style="font-size:36px;margin:0 0 8px;">🎤</p>
    <h1>Booking Confirmed!</h1>
    <p>Your room reservation has been submitted successfully.</p>
  </div>

  <div class="body">
    <p style="font-size:16px;color:#1e293b;margin-top:0">
      Hi <strong>{{ $bookingType === 'user' ? $booking->user->name : $booking->full_name }}</strong>,
    </p>
    <p style="color:#64748b;font-size:14px;line-height:1.6">
      Thank you for choosing <strong>KaraokeZone</strong>! Your booking request has been received and is currently pending review. Our team will confirm your booking within 1–2 hours.
    </p>

    <div class="ref-box">
      <small>Your Reference Number</small>
      <span>{{ $booking->reference_number }}</span>
    </div>

    <table class="details">
      <tr>
        <td>Room</td>
        <td>{{ $booking->room->name }}</td>
      </tr>
      <tr>
        <td>Date</td>
        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</td>
      </tr>
      <tr>
        <td>Time</td>
        <td>{{ $booking->start_time }} – {{ $booking->end_time }}</td>
      </tr>
      <tr>
        <td>Guests</td>
        <td>{{ $booking->num_guests }} pax</td>
      </tr>
      <tr>
        <td>Status</td>
        <td><span class="status-badge">Pending Approval</span></td>
      </tr>
      <tr>
        <td>Total Cost</td>
        <td><span class="cost">₱{{ number_format($booking->total_cost, 2) }}</span></td>
      </tr>
    </table>

    <div style="background:#eff6ff;border-radius:8px;padding:16px;margin-bottom:24px;">
      <p style="margin:0 0 8px;font-weight:600;color:#1d4ed8;font-size:13px;">📋 What to bring on your visit:</p>
      <ul style="margin:0;padding-left:18px;color:#334155;font-size:13px;line-height:1.8">
        <li>A valid government-issued ID</li>
        <li>Your reference number: <strong>{{ $booking->reference_number }}</strong></li>
        <li>Payment (we accept cash, GCash, and cards)</li>
      </ul>
    </div>

    <p style="color:#64748b;font-size:13px;">
      <strong>Cancellation policy:</strong> Cancellations made at least 24 hours before your booking are free of charge.
    </p>
  </div>

  <div class="footer">
    <p>Questions? Email us at <strong>hello@karaokeZone.com</strong> or call <strong>+63 912 345 6789</strong></p>
    <p>123 Karaoke St., Manila &bull; Open daily</p>
    <p style="margin-top:12px;color:#cbd5e1;font-size:11px;">&copy; {{ date('Y') }} KaraokeZone. All rights reserved.</p>
  </div>
</div>
</body>
</html>
