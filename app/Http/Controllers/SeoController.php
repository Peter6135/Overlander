<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Destination;
use App\Models\Package;

class SeoController extends Controller
{
    public function sitemap()
    {
        $urls = collect([
            ['loc' => route('home'), 'lastmod' => null],
            ['loc' => route('destinations.index'), 'lastmod' => null],
            ['loc' => route('packages.index'), 'lastmod' => null],
            ['loc' => route('articles.index'), 'lastmod' => null],
            ['loc' => route('about'), 'lastmod' => null],
            ['loc' => route('faq'), 'lastmod' => null],
            ['loc' => route('contact'), 'lastmod' => null],
            ['loc' => route('privacy'), 'lastmod' => null],
            ['loc' => route('terms'), 'lastmod' => null],
        ]);

        Destination::where('is_active', true)->get()->each(fn ($d) => $urls->push(['loc' => route('destinations.show', $d), 'lastmod' => $d->updated_at]));
        Package::where('is_active', true)->get()->each(fn ($p) => $urls->push(['loc' => route('packages.show', $p), 'lastmod' => $p->updated_at]));
        Article::where('is_published', true)->get()->each(fn ($a) => $urls->push(['loc' => route('articles.show', $a), 'lastmod' => $a->updated_at]));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url><loc>' . e($u['loc']) . '</loc>';
            if ($u['lastmod']) {
                $xml .= '<lastmod>' . $u['lastmod']->toAtomString() . '</lastmod>';
            }
            $xml .= "</url>\n";
        }
        $xml .= '</urlset>' . "\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /bookings',
            'Disallow: /cart',
            'Disallow: /settings',
            'Disallow: /email',
            'Disallow: /auth',
            '',
            'Sitemap: ' . route('sitemap'),
        ];

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
