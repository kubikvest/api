<?php

namespace Kubikvest\Manager;

use Kubikvest\Mapper\PointMapper;
use Kubikvest\Model\Point;

class PointManager
{
    const MIN = 0;
    const MAX = 1;
    const ACCURACY_MAX = 40;

    protected $mapper;

    public function __construct(PointMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param array  $coords
     * @param double $latitude
     * @param double $longitude
     *
     * @return bool
     */
    public function checkCoordinates(array $coords, $latitude, $longitude)
    {
        return $coords['latitude'][self::MIN] <= $latitude &&
            $latitude <= $coords['latitude'][self::MAX] &&
            $coords['longitude'][self::MIN] <= $longitude &&
            $longitude <= $coords['longitude'][self::MAX];
    }

    /**
     * @param int   $accuracy
     * @param float $minDistance
     *
     * @return bool
     */
    public function pointIncludedAccuracyRange($accuracy, $minDistance)
    {
        return !(self::ACCURACY_MAX < (int)$accuracy || $accuracy < $minDistance);
    }

    /**
     * @param array $coords
     * @param float $lat
     * @param float $lng
     *
     * @return array
     */
    public function calcDistanceToPointsSector(array $coords, $lat, $lng)
    {
        $points = [
            [$coords['latitude'][self::MIN], $coords['longitude'][self::MIN]],
            [$coords['latitude'][self::MIN], $coords['longitude'][self::MAX]],
            [$coords['latitude'][self::MAX], $coords['longitude'][self::MIN]],
            [$coords['latitude'][self::MAX], $coords['longitude'][self::MAX]],
        ];
        $res = [];
        foreach ($points as $point) {
            list($latPoint, $lngPoint) = $point;
            $distance = (string)$this->vincentyGreatCircleDistance($lat, $lng, $latPoint, $lngPoint);
            $res[$distance] = [
                'distance'  => $distance,
                'latitude'  => $latPoint,
                'longitude' => $lngPoint,
            ];
        }

        return $res;
    }

    /**
     * @param array $distances
     *
     * @return float
     */
    public function distanceBorderSector(array $distances)
    {
        sort($distances);
        list($a, $b) = $distances;
        $c = $this->vincentyGreatCircleDistance($a['latitude'], $a['longitude'], $b['latitude'], $b['longitude']);

        return $this->altitudeTriangle($a['distance'], $b['distance'], $c);
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     *
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     *
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public function vincentyGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
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

        return $angle * $earthRadius;
    }

    /**
     * @param float $a
     * @param float $b
     * @param float $c
     *
     * @return float
     */
    public function altitudeTriangle($a, $b, $c)
    {
        $p = 0.5 * ($a + $b + $c);
        $s = sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));

        return 2 * $s / $c;
    }

    /**
     * @param Point $point
     *
     * @return bool
     */
    public function isEmpty(Point $point)
    {
        return null === $point->pointId;
    }

    /**
     * @param Point $point
     * @param string $startTask 'Y-m-d H:i:s'
     *
     * @return int
     */
    public function getTimer(Point $point, $startTask)
    {
        $timer = 0;
        $start = new \DateTime($startTask);
        $since = $start->diff(new \DateTime());

        if (0 < $since->h) {
            return $timer;
        }

        foreach ($point->prompt as $k => $v) {
            if ($k > $since->i) {
                $min = $k - $since->i;
                $timer = $min * 60 - $since->s;
                break;
            }
        }

        return $timer;
    }

    /**
     * @param Point $point
     * @param string $startTask 'Y-m-d H:i:s'
     *
     * @return string
     */
    public function getPrompt(Point $point, $startTask)
    {
        $prompt = '';
        $start = new \DateTime($startTask);
        $since = $start->diff(new \DateTime());

        foreach ($point->prompt as $k => $v) {
            if ($since->i >= $k) {
                $prompt = $v;
            }
        }

        if (0 < $since->h) {
            $prompt = end($point->prompt);
        }

        return $prompt;
    }
}
