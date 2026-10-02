<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
@php
    $digits = preg_replace('/\D+/', '', $booking->guest_phone);
    if (str_starts_with($digits, '0')) {
        $digits = '62' . substr($digits, 1);
    }
    $rows = [
        'Booking' => '#' . $booking->id . ($booking->is_custom ? ' (custom trip request)' : ''),
        'Trip' => $booking->is_custom ? '-' : ($booking->package?->name ?? '-'),
        'Plan' => $booking->packagePlan?->name ?? '-',
        'Trip date' => $booking->trip_date->format('d M Y'),
        'Travelers' => $booking->pax,
        'Name' => $booking->guest_name,
        'Email' => $booking->guest_email,
        'Phone' => $booking->guest_phone,
    ];
@endphp
<body style="font-family: Arial, sans-serif; color: #1f2937; max-width: 520px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px; color: #ea580c;">New booking request</h1>
    <p>A traveler just sent a request on the Overlander website.</p>

    <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
        @foreach($rows as $label => $value)
        <tr>
            <td style="padding: 6px 0; color: #6b7280; width: 110px;">{{ $label }}</td>
            <td style="padding: 6px 0; font-weight: bold;">{{ $value }}</td>
        </tr>
        @endforeach
    </table>

    @if($booking->message)
    <p style="color: #6b7280; margin-bottom: 4px;">Message</p>
    <p style="margin-top: 0;">{{ $booking->message }}</p>
    @endif

    @if($booking->is_custom && $booking->destinations->isNotEmpty())
    <p style="color: #6b7280; margin-bottom: 4px;">Places of interest</p>
    <p style="margin-top: 0; font-weight: bold;">{{ $booking->destinations->pluck('name')->join(', ') }}</p>
    @endif

    @if($booking->custom_request)
    <p style="color: #6b7280; margin-bottom: 4px;">Custom request</p>
    <p style="margin-top: 0;">{{ $booking->custom_request }}</p>
    @endif

    <p style="margin-top: 24px;">
        <a href="https://wa.me/{{ $digits }}" style="color: #16a34a; font-weight: bold;">Chat the traveler on WhatsApp</a><br>
        <a href="{{ route('admin.bookings.index') }}" style="color: #ea580c;">Open bookings in the admin panel</a>
    </p>
</body>
</html>
