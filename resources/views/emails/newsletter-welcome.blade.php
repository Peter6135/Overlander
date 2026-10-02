<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; max-width: 480px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 20px; color: #ea580c;">Overlander</h1>

    <p>Thanks for subscribing! We will let you know about new routes, trips and travel stories from The Overlander Indonesia.</p>

    <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">

    <p>Terima kasih sudah berlangganan! Kami akan kabarin kamu soal rute baru, trip, dan cerita perjalanan dari The Overlander Indonesia.</p>

    <p style="margin-top: 24px; color: #6b7280; font-size: 13px;">
        <a href="{{ route('newsletter.unsubscribe', $subscriber->token) }}" style="color: #6b7280;">Unsubscribe / Berhenti berlangganan</a>
    </p>
</body>
</html>
