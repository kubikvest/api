<?php

namespace Kubikvest\Model\Geo;

class Coordinate
{
    protected $latitude = 0.0;
    protected $longitude = 0.0;

    public function __construct($latitude, $longitude)
    {
        $this->latitude  = (float) $latitude;
        $this->longitude = (float) $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
