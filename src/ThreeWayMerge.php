<?php

namespace Relaxed\Merge\ThreeWayMerge;

class ThreeWayMerge
{
    public function performMerge(array $ancestor, array $local, array $remote) {
        $merged = $ancestor;
        foreach ($ancestor as $key => $value) {
            if ($value == $remote[$key]) {
                $merged[$key] = $local[$key];
            }
            if ($value == $local[$key]) {
                $merged[$key] = $remote[$key];
            }
            if ($remote[$key] == $local[$key]) {
                $merged[$key] = $local[$key];
            }
        }
//        if ($conflict) {
//            throw new MergeConflictException();
//        }
        return $merged;
    }
}
