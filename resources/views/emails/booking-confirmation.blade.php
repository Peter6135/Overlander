<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; max-width: 480px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px; color: #ea580c;">{{ __('booking.email_heading') }}</h1>
    <p>{{ __('booking.email_greeting', ['name' => $booking->guest_name]) }}</p>
    <p>{{ __('booking.email_intro') }}</p>

    <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">{{ __('booking.booking_number') }}</td>
            <td style="padding: 6px 0; text-align: right; font-weight: bold;">{{ $booking->id }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">{{ __('booking.email_trip_label') }}</td>
            <td style="padding: 6px 0; text-align: right;">{{ $booking->is_custom ? __('booking.email_custom_trip') : $booking->package?->name }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">{{ __('booking.trip_date') }}</td>
            <td style="padding: 6px 0; text-align: right;">{{ $booking->trip_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">{{ __('booking.travelers') }}</td>
            <td style="padding: 6px 0; text-align: right;">{{ $booking->pax }}</td>
        </tr>
        @if($booking->total_price)
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">{{ __('booking.total_price') }}</td>
            <td style="padding: 6px 0; text-align: right; font-weight: bold;">${{ number_format($booking->total_price, 0) }}</td>
        </tr>
        @endif
        <tr>
            <td style="padding: 6px 0; color: #6b7280;">Status</td>
            <td style="padding: 6px 0; text-align: right;">{{ __('booking.status_' . $booking->status) }}</td>
        </tr>
    </table>

    <p>{{ __('booking.email_footer_intro') }}</p>
    <p><a href="{{ route('bookings.show', $booking) }}" style="color: #ea580c;">{{ __('booking.email_view_booking') }}</a></p>

    <p style="margin-top: 24px; color: #6b7280; font-size: 13px;">{{ __('booking.email_footer_note') }}</p>
</body>
</html>
