<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 04.10.18
 * Time: 20:38
 */

namespace JamChart;


class TrackObject
{
    /** @var \DateTime */
    public $date_retrieved;

    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var int */
    public $duration;

    /** @var int */
    public $artist_id;

    /** @var string */
    public $artist_name;

    /** @var string */
    public $artist_idstr;

    /** @var string */
    public $album_name;

    /** @var int */
    public $album_id;

    /** @var string */
    public $license_ccurl;

    /** @var int */
    public $position;

    /** @var \DateTime */
    public $release_date;

    /** @var string */
    public $album_image;

    /** @var string */
    public $audio;

    /** @var string */
    public $audio_download;

    /** @var string */
    public $pro_url;

    /** @var string */
    public $short_url;

    /** @var string */
    public $share_url;

    /** @var string */
    public $image;

    /** @var string */
    public $vocal_instrumental;

    /** @var string */
    public $lang;

    /** @var string */
    public $gender;

    /** @var string */
    public $acoustic_electric;

    /** @var string */
    public $speed;

    /** @var int */
    public $downloads;

    /** @var int */
    public $listened;

    /** @var int */
    public $playlisted;

    /** @var int */
    public $favorited;

    /** @var int */
    public $likes;

    /** @var int */
    public $dislikes;

    /** @var float */
    public $avgnote;

    /** @var int */
    public $notes;

    /** @var string */
    public $date_created;

    public function __construct()
    {
    }

    /**
     * @param $assoc array
     * @return TrackObject
     */
    public static function build($assoc) {
        $object = new TrackObject();

        $object->date_retrieved = $assoc['date_retrieved'];
        $object->id = intval($assoc['id']);
        $object->name = $assoc['name'];
        $object->duration = intval($assoc['duration']);
        $object->artist_id = intval($assoc['artist_id']);
        $object->artist_name = $assoc['artist_name'];
        $object->artist_idstr = $assoc['artist_idstr'];
        $object->album_name = $assoc['album_name'];
        $object->album_id = intval($assoc['album_id']);
        $object->license_ccurl = $assoc['license_ccurl'];
        $object->position = intval($assoc['position']);
        $object->release_date = $assoc['release_date'];
        $object->album_image = $assoc['album_image'];
        $object->audio = $assoc['audio'];
        $object->audio_download = $assoc['audio_download'];
        $object->pro_url = $assoc['pro_url'];
        $object->short_url = $assoc['short_url'];
        $object->share_url = $assoc['share_url'];
        $object->image = $assoc['image'];
        $object->vocal_instrumental = $assoc['vocal_instrumental'];
        $object->lang = $assoc['lang'];
        $object->gender = $assoc['gender'];
        $object->acoustic_electric = $assoc['acoustic_electric'];
        $object->speed = $assoc['speed'];
        $object->downloads = intval($assoc['downloads']);
        $object->listened = intval($assoc['listened']);
        $object->playlisted = intval($assoc['playlisted']);
        $object->favorited = intval($assoc['favorited']);
        $object->likes = intval($assoc['likes']);
        $object->dislikes = intval($assoc['dislikes']);
        $object->avgnote = floatval($assoc['avgnote']);
        $object->notes = intval($assoc['notes']);
        $object->date_created = $assoc['date_created'];

        return $object;
    }
}