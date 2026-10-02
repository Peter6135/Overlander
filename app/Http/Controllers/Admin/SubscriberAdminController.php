<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class SubscriberAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::latest();

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $subscribers = $query->paginate(25)->withQueryString();
        $activeCount = NewsletterSubscriber::whereNull('unsubscribed_at')->count();

        return view('admin.subscribers.index', compact('subscribers', 'activeCount'));
    }

    public function export()
    {
        $rows = NewsletterSubscriber::whereNull('unsubscribed_at')->orderBy('created_at')->get(['email', 'locale', 'created_at']);

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['email', 'language', 'subscribed_at']);
            foreach ($rows as $row) {
                fputcsv($out, [$row->email, $row->locale, $row->created_at->toDateTimeString()]);
            }
            fclose($out);
        }, 'overlander-subscribers-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber deleted.');
    }
}
