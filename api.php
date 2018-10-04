<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 04.10.18
 * Time: 18:23
 */

use Medoo\Medoo;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

const movement_columns = [
    'rownum',
    'date_retrieved',
    'id',
    'score',
    'move',
];

$container = new \Slim\Container;

$container['db'] = include("config/db.php");

$app = new \Slim\App($container);
$app->get('/rest/data', function(Request $request, Response $response, array $params) {
    /**
     * Variables
     *
     * @var \Slim\Http\Response $response
     * @var Medoo $db
     */
    $db = $this->get('db');

    $rows = $db->select(
        'v_latest_date',
        [
            'date_retrieved'
        ]
    );
    $latest_date = $rows[0]['date_retrieved'];

    $rows = $db->select(
        'v_previous_date',
        [
            'date_retrieved'
        ]
    );
    $previous_date = $rows[0]['date_retrieved'];

    $rows = $db->select(
        'v_best_entry',
        movement_columns
    );
    $best_entry = \JamChart\MovementObject::build($rows[0]);

    $rows = $db->select(
        'v_best_exit',
        movement_columns
    );
    $best_exit = \JamChart\MovementObject::build($rows[0]);

    $rows = $db->select(
        'v_biggest_movers',
        [
            'rownum'
        ]
    );
    $biggest_movers = array_map(function($row) {
        return intval($row['rownum']);
    }, $rows);

    $rows = $db->select(
        'movements',
        movement_columns,
        [
            'date_retrieved' => $latest_date,
            'LIMIT' => 50,
        ]
    );
    $chart = array_map(function($row) {
        $chartEntry = \JamChart\MovementObject::build($row);
        return $chartEntry;
    }, $rows);

    $ids = array_map(function($row) {
        return intval($row['id']);
    }, $rows);
    $ids[] = $best_entry->id;
    $rows = $db->select(
        'tracks',
        [
            'date_retrieved',
            'id',
            'name',
            'duration',
            'artist_id',
            'artist_name',
            'artist_idstr',
            'album_name',
            'album_id',
            'license_ccurl',
            'position',
            'release_date',
            'album_image',
            'audio',
            'audio_download',
            'pro_url',
            'short_url',
            'share_url',
            'image',
            'vocal_instrumental',
            'lang',
            'gender',
            'acoustic_electric',
            'speed',
            'downloads',
            'listened',
            'playlisted',
            'favorited',
            'likes',
            'dislikes',
            'avgnote',
            'notes',
            'date_created',
        ],
        [
            'date_retrieved' => $latest_date,
            'id' => $ids
        ]
    );
    $tracks = [];
    foreach ($rows as $row) {
        $track = \JamChart\TrackObject::build($row);
        $tracks[intval($row['id'])] = $track;
    }

    $rows = $db->select(
        'tracks',
        [
            'date_retrieved',
            'id',
            'name',
            'duration',
            'artist_id',
            'artist_name',
            'artist_idstr',
            'album_name',
            'album_id',
            'license_ccurl',
            'position',
            'release_date',
            'album_image',
            'audio',
            'audio_download',
            'pro_url',
            'short_url',
            'share_url',
            'image',
            'vocal_instrumental',
            'lang',
            'gender',
            'acoustic_electric',
            'speed',
            'downloads',
            'listened',
            'playlisted',
            'favorited',
            'likes',
            'dislikes',
            'avgnote',
            'notes',
            'date_created',
        ],
        [
            'date_retrieved' => $previous_date,
            'id' => $best_exit->id,
        ]
    );
    $row = $rows[0];
    $track = \JamChart\TrackObject::build($row);
    $tracks[intval($row['id'])] = $track;

    return $response->withJson(
        [
            'status' => 'ok',
            'latest_date' => $latest_date,
            'previous_date' => $previous_date,
            'best_entry' => $best_entry,
            'best_exit' => $best_exit,
            'biggest_movers' => $biggest_movers,
            'chart' => $chart,
            'tracks' => $tracks,
        ]
    );
});

try {
    $app->run();
} catch (\Slim\Exception\MethodNotAllowedException $e) {

} catch (\Slim\Exception\NotFoundException $e) {

} catch (\Exception $e) {

}