<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcomeMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validateWithBag('newsletter', ['email' => 'required|email|max:255']);

        // Hidden field that real visitors never fill in; bots usually do.
        if ($request->filled('website')) {
            return back()->with('success', __('newsletter.subscribed'));
        }

        $subscriber = NewsletterSubscriber::firstOrNew(['email' => strtolower($data['email'])]);
        $isNew = ! $subscriber->exists;

        if ($isNew) {
            $subscriber->token = Str::random(40);
        }

        $subscriber->locale = app()->getLocale();
        $subscriber->unsubscribed_at = null;
        $subscriber->save();

        if ($isNew) {
            try {
                Mail::to($subscriber->email)->send(new NewsletterWelcomeMail($subscriber));
            } catch (\Throwable $e) {
                Log::error('Newsletter welcome email failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', __('newsletter.subscribed'));
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (! $subscriber) {
            abort(404);
        }

        $subscriber->update(['unsubscribed_at' => now()]);

        return redirect()->route('home')->with('success', __('newsletter.unsubscribed'));
    }
}
