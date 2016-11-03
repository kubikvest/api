<?php

namespace Kubikvest\Model;

class Point
{
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
}
