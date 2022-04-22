<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortenUrl;

class UrlShortenerController extends Controller
{
    public function index() {
        return view('url-shortener');
    }

    public function show($hash) {

        $shortenUrl = ShortenUrl::where('hash', $hash)->first();

        // Increase visits count by 1 and update the database.
        $shortenUrl->visits += 1;
        $shortenUrl->save();

        return redirect($shortenUrl->url);
    }

    public function shorten(Request $request) {

        $requestedUrl = $request->url;

        // Check if the URL exists in the database.
        $shortenUrl = ShortenUrl::where('url', $requestedUrl)->first();

        // If URL already exists, return the shortened URL.
        // Else generate a shortened URL, store in database and return it.
        if($shortenUrl) {

            return url($shortenUrl->hash);

        } else {

            $hash = $this->generateHash();
            ShortenUrl::create([
                'url' => $requestedUrl,
                'hash' => $hash
            ]);

            $shortenUrl = ShortenUrl::where('url', $requestedUrl)->first();

            return url($shortenUrl->hash);

        }

    }

    public function generateHash() {

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $hash = substr(str_shuffle($permitted_chars), 0, 6);

        // Check if current hash exists in the database.
        $data = ShortenUrl::where('hash', $hash)->first();

        // If hash already exists, generate a new one.
        if($data != null) {
            $this->generateHash();
        }

        return $hash;

    }

}
