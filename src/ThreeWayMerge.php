<?php

namespace Relaxed\Merge\ThreeWayMerge;

use Exception;

class ThreeWayMerge
{

    /**
     * Performs automatic merge if no conflict arises else throws exception.
     *
     * @param array $ancestor
     * @param array $local
     * @param array $remote
     *
     * @return array
     * @throws Exception
     */
    public function performMerge(array $ancestor, array $local, array $remote)
    {
        // Returning a new Array for now. Can return the modified ancestor as well.
        $merged = [];
        $conflict = false;
        foreach ($ancestor as $key => $value) {
            if (is_array($value)) {
                $merged[$key] = $this->performMerge(
                    $value,
                    $local[$key],
                    $remote[$key]
                );
            } else {
                $merged[$key] = $this->multiLineMerge($ancestor[$key],$local[$key],$remote[$key], $key);
            }
        }
        // Throw Exception if there is a conflict.
        if ($conflict) {
            throw new Exception('A merge conflict has occured.');
        }
        return $merged;
    }

    public function multiLineMerge($x, $y, $z, $key)
    {
        $merged = [];
        if(strpos($x, "\n") !== FALSE) {
            $ancestor = explode("\n", $x);
        }
        if(strpos($y, "\n") !== FALSE) {
            $local = explode("\n", $y);
        }
        if(strpos($z, "\n") !== FALSE) {
            $remote = explode("\n", $z);
        }
        if(isset($remote) || isset($ancestor) || isset($local)) {
            foreach ($ancestor as $key => $value) {
                if ($ancestor[$key] == $local[$key]) {
                    $merged[$key] = $remote[$key];
                } elseif ($ancestor[$key] == $remote[$key]) {
                    $merged[$key] = $local[$key];
                } elseif ($local[$key] == $remote[$key]) {
                    $merged[$key] = $local[$key];
                } else {
                    echo "CONFLICT";
                }
            }
            $merged[$key] = implode(PHP_EOL, $merged);
        }
        else{
            if ($x == $y) {
                $merged[$key] = $z;
            } elseif ($x == $z) {
                $merged[$key] = $y;
            } elseif ($y == $z) {
                $merged[$key] = $y;
            }
            else {
                throw new Exception("A conflict has occured");
            }
        }

        return $merged[$key];
    }
}
