<?php

namespace Kubikvest\Model;

class Point
{
    const MIN = 0;
    const MAX = 1;
    const ACCURACY_MAX = 40;

    public $pointId = null;
    public $title = null;
    public $description = null;
    public $coords = [];
    public $prompt = null;

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return null === $this->pointId;
    }

    /**
     * @param double $latitude
     * @param double $longitude
     *
     * @return bool
     */
    public function checkCoordinates($latitude, $longitude)
    {
        return $this->coords['latitude'][self::MIN] <= $latitude &&
            $latitude <= $this->coords['latitude'][self::MAX] &&
            $this->coords['longitude'][self::MIN] <= $longitude &&
            $longitude <= $this->coords['longitude'][self::MAX];
    }

    /**
     * @param int   $accuracy
     * @param float $minDistance
     *
     * @return bool
     */
    public function checkAccuracy($accuracy, $minDistance)
    {
        return !(Point::ACCURACY_MAX < (int) $accuracy || $accuracy < $minDistance);
    }

    /**
     * @param string $startTask 'Y-m-d H:i:s'
     *
     * @return int
     */
    public function getTimer($startTask)
    {
        $timer = 0;
        $start = new \DateTime($startTask);
        $since = $start->diff(new \DateTime());

        if (0 < $since->h) {
            return $timer;
        }

        foreach ($this->prompt as $k => $v) {
            if ($k > $since->i) {
                $min = $k - $since->i;
                $timer = $min * 60 - $since->s;
                break;
            }
        }

        return $timer;
    }

    /**
     * @param string $startTask 'Y-m-d H:i:s'
     *
     * @return string
     */
    public function getPrompt($startTask)
    {
        $prompt = '';
        $start = new \DateTime($startTask);
        $since = $start->diff(new \DateTime());

        foreach ($this->prompt as $k => $v) {
            if ($since->i >= $k) {
                $prompt = $v;
            }
        }

        if (0 < $since->h) {
            $prompt = end($this->prompt);
        }

        return $prompt;
    }

    /**
     * @param float $lat
     * @param float $lng
     *
     * @return array
     */
    public function calcDistanceToPointsSector($lat, $lng)
    {
        $points = [
            [$this->coords['latitude'][self::MIN], $this->coords['longitude'][self::MIN]],
            [$this->coords['latitude'][self::MIN], $this->coords['longitude'][self::MAX]],
            [$this->coords['latitude'][self::MAX], $this->coords['longitude'][self::MIN]],
            [$this->coords['latitude'][self::MAX], $this->coords['longitude'][self::MAX]],
        ];
        $res = [];
        foreach ($points as $point) {
            list($latPoint, $lngPoint) = $point;
            $res[] = $this->vincentyGreatCircleDistance($lat, $lng, $latPoint, $lngPoint);
        }

        return $res;
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
    protected function vincentyGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
    ){
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo   = deg2rad($latitudeTo);
        $lonTo   = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);

        return $angle * $earthRadius;
    }
}
