<?php

namespace Relaxed\Merge\ThreeWayMerge;

use Exception;

class ThreeWayMerge
{
    public function performMerge(array $ancestor, array $local, array $remote)
    {
        $merged = $ancestor;
        $conflict = FALSE;
        foreach ($ancestor as $key=>$value) {
            if ($value == $remote[$key]) {
                $merged[$key] = $local[$key];
            } elseif ($value == $local[$key]) {
                $merged[$key] = $remote[$key];
            } elseif ($remote[$key] == $local[$key]) {
                $merged[$key] = $local[$key];
            } else {
                $conflict = TRUE;
            }
        }
        if ($conflict) {
            throw new Exception('A merge conflict has occured.');
        }
        return $merged;
    }
}

