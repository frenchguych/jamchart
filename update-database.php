<?php

require 'vendor/autoload.php';

use Medoo\Medoo;
use JamChart\CurlHelper;

$database = include("config/db.php");

$curl = new CurlHelper();

$now = new DateTime();
$then = new DateTime();
$then->sub(new DateInterval("P3M"));
$datebetween = $then->format('Y-m-d') . "_" . $now->format('Y-m-d');

$params = [
    "client_id" => include("config/jamendo.php"),
    "datebetween" => $datebetween,
    "order" => "releasedate_desc",
    "type" => "single+albumtrack",
    "limit" => 200,
    "imagesize" => 600,
    "include" => "stats+musicinfo",
    "fullcount" => true
];

$url = "https://api.jamendo.com/v3.0/tracks/";

$offset = 0;
$calls = 0;
$fullcount = 0;

while (true) {
    $data = $curl->fetch($url, $params);

    if (empty($data)) {
        die("Error: empty data return by the API call");
    }
    
    $json = json_decode($data);
    $headers = $json->headers;
    
    if (empty($headers)) {
        die("Error: empty headers");
    }
    
    $status = $headers->status;
    if ($status != "success") {
        die("Error: status " . $status);
    }
    
    $code = $headers->code;
    if ($code != 0) {
        die("Error: code " . $code);
    }

    $results_count = $headers->results_count;
    if ($params['fullcount'] == true) {
        $fullcount = ceil($headers->results_fullcount / $results_count);
    }

    $calls++;
    echo "\n";
    echo "API call #" . $calls . " of " . $fullcount . "\n";

    $results = $json->results;

    foreach ($results as $result) {
        echo $result->id . " ";

        $database->insert('tracks', [
            "date_retrieved" => $now->format('Y-m-d'),
            "id" => $result->id,
            "name" => $result->name,
            "duration" => $result->duration,
            "artist_id" => $result->artist_id,
            "artist_name" => $result->artist_name,
            "artist_idstr" => $result->artist_idstr,
            "album_name" => $result->album_name,
            "album_id" => $result->album_id,
            "license_ccurl" => $result->license_ccurl,
            "position" => $result->position,
            "release_date" => $result->releasedate,
            "album_image" => $result->album_image,
            "audio" => $result->audiodownload,
            "audio_download" => $result->audiodownload,
            "pro_url" => $result->prourl,
            "short_url" => $result->shorturl,
            "share_url" => $result->shareurl,
            "image" => $result->image,
            "vocal_instrumental" => $result->musicinfo->vocalinstrumental,
            "lang" => $result->musicinfo->lang,
            "gender" => $result->musicinfo->gender,
            "acoustic_electric" => $result->musicinfo->acousticelectric,
            "speed" => $result->musicinfo->speed,
            "downloads" => $result->stats->rate_downloads_total,
            "listened" => $result->stats->rate_listened_total,
            "playlisted" => $result->stats->playlisted,
            "favorited" => $result->stats->favorited,
            "likes" => $result->stats->likes,
            "dislikes" => $result->stats->dislikes,
            "avgnote" => $result->stats->avgnote,
            "notes" => $result->stats->notes
        ]);

/*
        $tags = $result->musicinfo->tags;
        $dbtags = [];
        foreach($tags as $type => $values) {
            foreach($values as $value) {
                $dbtags[] = [
                    "date_retrieved" => $now->format('Y-m-d'),
                    "track_id" => $result->id,
                    "type" => $type,
                    "value" => $value
                ];
            }
        }
        $database->insert('tags', $dbtags);
*/
    }
    echo "\n";
    
    if ($results_count < 200) {
        break;
    }
    $offset += $results_count;
    $params['offset'] = $offset;
    $params['fullcount'] = false;
}

echo "\n";
echo "All done !\n";

