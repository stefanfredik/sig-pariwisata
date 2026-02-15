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

        return response()->view('sitemap', [
            'objekWisatas' => $objekWisatas,
            'events' => $events,
        ])->header('Content-Type', 'text/xml');
    }
}
