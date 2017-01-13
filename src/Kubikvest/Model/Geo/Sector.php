<?php

namespace Kubikvest\Model\Geo;

class Sector
{
    protected $latitudeRange = null;
    protected $longitudeRange = null;

    /**
     * Sector constructor.
     *
     * @param Distance $diagonal
     */
    public function __construct(Distance $diagonal)
    {
        $this->latitudeRange = new Range(
            $diagonal->getFrom()->getLatitude(),
            $diagonal->getTo()->getLatitude()
        );
        $this->longitudeRange = new Range(
            $diagonal->getFrom()->getLongitude(),
            $diagonal->getTo()->getLongitude()
        );
    }

    /**
     * @return Range
     */
    public function getLatitudeRange()
    {
        return $this->latitudeRange;
    }

    /**
     * @return Range
     */
    public function getLongitudeRange()
    {
        return $this->longitudeRange;
    }
}
