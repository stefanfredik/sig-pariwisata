<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ObjekWisata;
use App\Models\Event;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $objekWisatas = ObjekWisata::latest()->get();
        $events = Event::latest()->get();

        $content = view('sitemap', [
            'objekWisatas' => $objekWisatas,
            'events' => $events,
        ])->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
