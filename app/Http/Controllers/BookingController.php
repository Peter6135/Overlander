<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmationMail;
use App\Mail\NewBookingAdminMail;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with(['package', 'packagePlan'])
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Package $package)
    {
        $package->load('plans', 'destinations');

        $cart = session('cart');
        $cart = ($cart && $cart['package_id'] === $package->id) ? $cart : null;

        return view('bookings.create', compact('package', 'cart'));
    }

    public function store(Request $request, Package $package)
    {
        $validator = Validator::make($request->all(), [
            'package_plan_id' => 'required|exists:package_plans,id',
            'pax' => 'required|integer|min:1|max:20',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'trip_date' => 'required|date|after:today',
            'message' => 'nullable|string|max:1000',
        ]);

        $validator->after(function ($validator) use ($request, $package) {
            $remaining = $package->remainingCapacity($request->trip_date);
            if ($remaining !== null && (int) $request->pax > $remaining) {
                $validator->errors()->add('pax', __('booking.slots_left_limited_js', ['n' => $remaining]));
            }
        });

        $data = $validator->validate();

        $plan = $package->plans()->findOrFail($data['package_plan_id']);
        $totalPrice = $plan->price * $data['pax'];

        $booking = auth()->user()->bookings()->create([
            ...$data,
            'package_id' => $package->id,
            'total_price' => $totalPrice,
            'payment_method' => 'whatsapp',
            'status' => 'pending',
        ]);

        session()->forget('cart');

        $this->sendMail($booking->guest_email, new BookingConfirmationMail($booking), $booking);
        $this->notifyAdmin($booking);

        return redirect()->route('bookings.show', $booking)
            ->with('success', __('flash.booking_created'));
    }

    public function createCustom()
    {
        return view('bookings.create-custom');
    }

    public function storeCustom(Request $request)
    {
        $data = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'trip_date' => 'required|date|after:today',
            'pax' => 'required|integer|min:1|max:20',
            'custom_request' => 'required|string|max:2000',
        ]);

        $booking = auth()->user()->bookings()->create([
            ...$data,
            'is_custom' => true,
            'payment_method' => 'whatsapp',
            'status' => 'pending',
        ]);

        $this->notifyAdmin($booking);

        return redirect()->route('bookings.show', $booking)
            ->with('success', __('flash.custom_trip_received'));
    }

    private function notifyAdmin(Booking $booking): void
    {
        $to = config('booking.notify_email');

        if ($to) {
            $this->sendMail($to, new NewBookingAdminMail($booking), $booking);
        }
    }

    private function sendMail(string $to, \Illuminate\Mail\Mailable $mail, Booking $booking): void
    {
        try {
            Mail::to($to)->send($mail);
        } catch (\Throwable $e) {
            Log::error('Booking email failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'mail' => class_basename($mail),
            ]);
        }
    }

    public function show(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        $booking->load('package', 'packagePlan', 'review');

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless($booking->canModify(), 403, __('flash.cannot_modify'));

        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless($booking->canModify(), 403, __('flash.cannot_modify'));

        $validator = Validator::make($request->all(), [
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'trip_date' => 'required|date|after:today',
            'pax' => 'required|integer|min:1|max:20',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($booking->package_id) {
            $validator->after(function ($validator) use ($request, $booking) {
                $remaining = $booking->package->remainingCapacity($request->trip_date, excludeBookingId: $booking->id);
                if ($remaining !== null && (int) $request->pax > $remaining) {
                    $validator->errors()->add('pax', __('booking.slots_left_limited_js', ['n' => $remaining]));
                }
            });
        }

        $data = $validator->validate();

        if ($booking->packagePlan) {
            $data['total_price'] = $booking->packagePlan->price * $data['pax'];
        }

        $booking->update($data);

        return redirect()->route('bookings.show', $booking)->with('success', __('flash.booking_updated'));
    }

    public function cancel(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless($booking->canModify(), 403, __('flash.cannot_cancel'));

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.index')
            ->with('info', __('flash.booking_cancelled'));
    }
}
