<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingStatusMail;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'package', 'packagePlan', 'destinations'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q
                ->where('guest_name', 'like', "%{$s}%")
                ->orWhere('guest_email', 'like', "%{$s}%"));
        }

        $bookings = $query->paginate(20)->withQueryString();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $previous = $booking->status;
        $booking->update(['status' => $request->status]);

        $message = "Booking #{$booking->id} status updated.";

        if ($previous !== $booking->status && $booking->status !== 'pending' && $booking->guest_email) {
            try {
                Mail::to($booking->guest_email)->send(new BookingStatusMail($booking));
                $message .= " Email sent to {$booking->guest_email}.";
            } catch (\Throwable $e) {
                Log::error('Booking status email failed: ' . $e->getMessage(), ['booking_id' => $booking->id]);
                $message .= ' The status was saved, but the email to the traveler could not be sent.';
            }
        }

        return back()->with('success', $message);
    }
}
