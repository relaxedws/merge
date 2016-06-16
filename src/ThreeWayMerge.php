<?php

namespace Relaxed\Merge\ThreeWayMerge;

use Exception;

class ThreeWayMerge
{
    public function performMerge(array $ancestor, array $local, array $remote)
    {
        // Returning a new Array for now. Can return the modified ancestor as well.
        $merged = [];
        foreach ($ancestor as $key => $value) {
            if (is_array($value)) {
                $merged[$key] = $this->performMerge(
                    $value,
                    $local[$key],
                    $remote[$key]
                );
            } else {
                $merged[$key] = $this->Merge($ancestor[$key],$local[$key],$remote[$key], $key);
                if ($merged[$key] == NULL){
                    unset($merged[$key]);
                }
            }
        }
        return $merged;
    }

    protected function Merge($x, $y, $z, $key)
    {
        $ancestor = (strpos($x, "\n") !== FALSE ? explode("\n", $x) : array($x));
        $local = (strpos($y, "\n") !== FALSE ? explode("\n", $y) : array($y));
        $remote = (strpos($z, "\n") !== FALSE ? explode("\n", $z) : array($z));
        $count_ancestor = count($ancestor);
        $count_remote = count($remote);
        $count_local = count($local);
        if($count_ancestor != $count_local && $count_local!= $count_remote && $count_ancestor != $count_remote){
            $merged = $this->linesModified($ancestor,$local,$remote,$count_ancestor,$count_local,$count_remote);
        }
        else {
            $count = $count_ancestor == $count_local ? $count_remote : $count_local;
            echo "$count_ancestor => $count_local => $count_remote => $count\n";
            $merged = $count > $count_ancestor ? $this->linesAdded($ancestor, $local, $remote, $count, $count_ancestor, $count_local) : $this->linesRemovedOrModified($ancestor, $local, $remote, $count);
        }
        $merged[$key] = implode(PHP_EOL, $merged);
        return $merged[$key];
    }

    protected function linesAdded(array $ancestor,array $local, array $remote, $count, $count_ancestor, $count_local)
    {
        $merged = [];
        $counter = 0;
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
        for ($i = $count_ancestor; $i < $count; $i++) {
            $count == $count_local ? $merged[$i] = $local[$i] : $merged[$i] = $remote[$i];
        }
        return $merged;
    }

    protected function linesRemovedOrModified(array $ancestor,array $local, array $remote, $count)
    {
        $merged = [];
        $counter = 0;
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
        return $merged;
    }

    protected function linesModified(array $ancestor,array $local, array $remote, $count_ancestor, $count_local, $count_remote)
    {
        $merged = [];
        $count_array = [$count_ancestor,$count_local,$count_remote];
        sort($count_array);
        $counter = 0;
        $mincount = min($count_local,$count_ancestor,$count_remote);
        for($key = 0 ; $key<$mincount ; $key++){
            if ($ancestor[$key] == $local[$key]) {
                $merged[$key] = $remote[$key];
            } elseif ($ancestor[$key] == $remote[$key] || $local[$key] == $remote[$key]) {
                $merged[$key] = $local[$key];
            } else {
                throw new Exception("A conflict has occured");
            }
        }
        for ($key = $mincount; $key<$count_array[1]; $key++){
            if ($ancestor[$key] == $local[$key]) {
                $merged[$key] = $remote[$key];
            } elseif ($ancestor[$key] == $remote[$key] || $local[$key] == $remote[$key]) {
                $merged[$key] = $local[$key];
            } else {
                throw new Exception("A conflict has occured");
            }
        }
        for ($i = $count_array[1]; $i < $count_array[2]; $i++) {
            $count == $count_local ? $merged[$i] = $local[$i] : $merged[$i] = $remote[$i];
        }
        return $merged;
    }
}
