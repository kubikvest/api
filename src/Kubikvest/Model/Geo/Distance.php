<?php

namespace Kubikvest\Model\Geo;

class Distance
{
    /**
     * Mean earth radius in [m]
     * @var int
     */
    const EARTH_RADIUS = 6371000;

    /**
     * @var Coordinate
     */
    protected $from = null;
    protected $to = null;
    protected $value = 0.0;

    public function __construct(Coordinate $from, Coordinate $to)
    {
        $this->from = $from;
        $this->to   = $to;

        $this->value = $this->vincentyGreatCircleDistance(
            $from->getLatitude(),
            $from->getLongitude(),
            $to->getLatitude(),
            $to->getLongitude()
        );
    }

    /**
     * @return Coordinate
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return Coordinate
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     *
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     *
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public function vincentyGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo
    )
    {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo   = deg2rad($latitudeTo);
        $lonTo   = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);

        return $angle * self::EARTH_RADIUS;
    }
}
