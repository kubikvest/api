<?php

namespace Kubikvest\Model;

class Quest
{
    public $questId = null;
    public $title = null;
    public $description = null;
    public $picture = null;
    public $points = [];

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return null === $this->questId;
    }

    public function getPoint()
    {
        $point = new Point();


    }

    /**
     * @param string $currentPoint
     *
     * @return string
     */
    public function nextPoint($currentPoint)
    {
        $index = array_keys($this->points, $currentPoint)[0];
        if ($index == count($this->points) - 1) {
            return end($this->points);
        } else {
            return $this->points[$index + 1];
        }
    }
}
