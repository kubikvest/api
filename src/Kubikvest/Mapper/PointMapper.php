<?php

namespace Kubikvest\Mapper;

use Kubikvest\Model\Point;

/**
 * Class Point
 * @package Kubikvest\Mapper
 */
class PointMapper
{
    protected $db;
    /**
     * @param $db
     */
    public function __construct(array $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $pointId
     *
     * @return Point
     */
    public function getPoint($pointId)
    {
        $data  = $this->db[$pointId];
        $point = new Point();

        $point->pointId     = $pointId;
        $point->title       = $data['title'];
        $point->description = $data['description'];
        $point->coords      = $data['coords'];
        $point->prompt      = $data['prompt'];

        return $point;
    }

    /**
     * @return array
     */
    public function getPoints()
    {
        return $this->db;
    }
}
