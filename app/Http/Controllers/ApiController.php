<?php

namespace App\Http\Controllers;

use App\Models\Shortener;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Util\Shortener as ShortUrl;

class ApiController extends Controller
{
    public function getAllShorteners()
    {
        // logic to get all Shortened URL's
        $shortenedUrls = Shortener::select('id', 'long_url', 'short_url', 'created_at')->get();
        return response()->json([
            "message" => "ok",
            "data" => $shortenedUrls
        ], 200);
    }

    public function createShortener(Request $request) {
        // logic to create one Shortened URL
        $long_url = $request->input('long_url');
        if( ! preg_match('/^http(s)?:\/\/[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $long_url) ){
            return response()->json([
                "message" => "URL not correct format"
            ], 400);
        }

        // If the URL has already been shortened, then return the equivalent shortened URL in a JSON response with HTTP status 200

        $found_url = Shortener::where('long_url', $long_url)->first();
        if (!empty($found_url->long_url)) {
            return response()->json([
                "message" => "ok",
                "data" => $found_url->short_url
            ], 200);
        } else {

            // If URL is new, shorten the URL and store it, then return the shortened URL in a JSON response with HTTP status 201
            $newShortenedUrl = (new ShortUrl())->createShortCode();

            $newUrl = new Shortener;
            $newUrl->long_url = $long_url;
            $newUrl->short_url = $newShortenedUrl;
            $newUrl->created_at = Carbon::now();
            $newUrl->save();

            return response()->json([
                "message" => "ok",
                "data" => $newShortenedUrl
            ], 201);
        }

    }

    public function deleteShortener(Request $request)
    {
        // logic to delete one Shortened URL
        $url = $request->input('short_url');
        $found_url = Shortener::where('short_url', $url)->first();

        if ($found_url !== NULL) {
            $shortenedUrl = Shortener::find($found_url->id);
            $shortenedUrl->delete();

            return response()->json([
                "message" => "URL deleted",
                "data" => $url
            ], 200);
        } else {

            return response()->json([
                "message" => "URL not found",
                "data" => (isset($url)) ? $url : ''
            ], 404);
        }

    }

}
