<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 04.10.18
 * Time: 19:17
 */

namespace JamChart;


class MovementObject
{
    /** @var int */
    public $rownum;

    /** @var \DateTime */
    public $date_retrieved;

    /** @var int */
    public $id;

    /** @var int */
    public $score;

    /** @var int */
    public $move;
    
    public function __construct()
    {
    }

    /**
     * Builds an object from Medoo's result.
     *
     * @param $assoc array
     * @return MovementObject
     */
    public static function build($assoc) {
        $object = new \JamChart\MovementObject();
        $object->rownum = intval($assoc['rownum']);
        $object->date_retrieved = $assoc['date_retrieved'];
        $object->id = intval($assoc['id']);
        $object->score = intval($assoc['score']);
        $object->move = intval($assoc['move']);

        return $object;
    }
}