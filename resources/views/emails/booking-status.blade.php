<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
@php
    $trip = $booking->is_custom ? 'Custom trip' : ($booking->package?->name ?? '-');
    $date = $booking->trip_date->format('d M Y');
    $copy = [
        'confirmed' => [
            'en' => "Good news! Your booking #{$booking->id} for {$trip} on {$date} is confirmed. Our team will stay in touch with you on WhatsApp about the final details.",
            'id' => "Kabar baik! Pesananmu #{$booking->id} untuk {$trip} pada {$date} sudah dikonfirmasi. Tim kami akan menghubungimu lewat WhatsApp soal detail akhirnya.",
        ],
        'cancelled' => [
            'en' => "Your booking #{$booking->id} for {$trip} on {$date} has been cancelled. If this is unexpected, please message us on WhatsApp.",
            'id' => "Pesananmu #{$booking->id} untuk {$trip} pada {$date} telah dibatalkan. Kalau ini di luar dugaanmu, silakan hubungi kami lewat WhatsApp.",
        ],
        'completed' => [
            'en' => "Thanks for travelling with us! Your trip #{$booking->id} ({$trip}, {$date}) is marked as completed. We would love to hear how it went: you can leave a review on the destination page.",
            'id' => "Terima kasih sudah ikut trip bareng kami! Perjalananmu #{$booking->id} ({$trip}, {$date}) sudah selesai. Kami senang kalau kamu mau berbagi ceritanya lewat ulasan di halaman destinasi.",
        ],
    ][$booking->status] ?? [
        'en' => "There is an update on your booking #{$booking->id}. Open it to see the details.",
        'id' => "Ada pembaruan pada pesananmu #{$booking->id}. Buka untuk melihat detailnya.",
    ];
@endphp
<body style="font-family: Arial, sans-serif; color: #1f2937; max-width: 480px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px; color: #ea580c;">Overlander</h1>

    <p>Hi {{ $booking->guest_name }},</p>
    <p>{{ $copy['en'] }}</p>

    <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">

    <p>Halo {{ $booking->guest_name }},</p>
    <p>{{ $copy['id'] }}</p>

    <p style="margin-top: 24px;">
        <a href="{{ route('bookings.show', $booking) }}" style="color: #ea580c;">View booking / Lihat pesanan</a><br>
        <a href="https://wa.me/{{ config('booking.whatsapp_number') }}" style="color: #16a34a;">WhatsApp</a>
    </p>
</body>
</html>
