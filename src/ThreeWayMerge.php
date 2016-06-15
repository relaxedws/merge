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

    /**
     * Handles the merge operations
     *
     * @param $x
     * @param $y
     * @param $z
     * @param $key
     *
     * @return mixed
     * @throws Exception
     */
    protected function multiLineMerge($x, $y, $z, $key)
    {
        if(strpos($x, "\n") !== FALSE) {
            $ancestor = explode("\n", $x);
        }
        else{
            $ancestor = array($x);
        }
        if(strpos($y, "\n") !== FALSE) {
            $local = explode("\n", $y);
        }

        else{
            $local = array($y);
        }
        if(strpos($z, "\n") !== FALSE) {
            $remote = explode("\n", $z);
        }

        else{
            $remote = array($z);
        }
//        print_r($ancestor);
//        if(isset($remote) || isset($ancestor) || isset($local)) {
            $count_ancestor = count($ancestor);
            $count_remote = count($remote);
            $count_local = count($local);
            if($count_ancestor == $count_local){
                $count = $count_remote;
            } elseif ($count_ancestor == $count_remote || $count_remote == $count_local){
                $count = $count_local;
            }
            $counter = 0;
            if($count > $count_ancestor) {
                foreach ($ancestor as $key => $value) {
                    $counter = $counter + 1;
                    if ($ancestor[$key] == $local[$key]) {
                        $merged[$key] = $remote[$key];
                    } elseif ($ancestor[$key] == $remote[$key] || $local[$key] == $remote[$key]) {
                        $merged[$key] = $local[$key];
                    } else {
                        throw new Exception("A conflict has occured");
                    }
                }
                for ($i = $count-$count_ancestor; $i < $count; $i++) {
                    $count == $count_local ? $merged[$i] = $local[$i] : $merged[$i] = $remote[$i];
                }
            }
            else {
                foreach ($ancestor as $key => $value) {
                    $counter = $counter + 1;
                    if ($ancestor[$key] == $local[$key]) {
                        $merged[$key] = $remote[$key];
                    } elseif ($ancestor[$key] == $remote[$key] || $local[$key] == $remote[$key]) {
                        $merged[$key] = $local[$key];
                    } else {
                        throw new Exception("A conflict has occured");
                    }
                    if ($counter >= $count) {
                        break;
                    }
                }
            }
            $merged[$key] = implode(PHP_EOL, $merged);
//        }
//        else {
//            if ($x == $y) {
//                $merged[$key] = $z;
//            } elseif ($x == $z || $y == $z) {
//                $merged[$key] = $y;
//            } else {
//                throw new Exception("A conflict has occured");
//            }
//        }
        return $merged[$key];
    }
}
