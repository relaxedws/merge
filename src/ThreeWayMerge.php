<?php

namespace Relaxed\Merge\ThreeWayMerge;

use Exception;

class ThreeWayMerge
{
    /**
     * Perform merge on associative array
     *
     * @param array $ancestor
     * @param array $local
     * @param array $remote
     *
     * @return array
     */
    public function performMerge(array $ancestor, array $local, array $remote)
    {
        // Returning a new Array for now. Can return the modified ancestor as well.
        $merged = [];
        foreach ($ancestor as $key => $value) {
            // Checks if the value contains an array itself.
            if (is_array($value)) {
                $merged[$key] = $this->performMerge(
                    $value,
                    $local[$key],
                    $remote[$key]
                );
            } else {
                $merged[$key] = $this->merge(
                    $ancestor[$key],
                    $local[$key],
                    $remote[$key],
                    $key
                );
                //If a key doesn't have any value, unset the key.
                if ($merged[$key] == null) {
                    unset($merged[$key]);
                }
            }
        }
        return $merged;
    }

    /**
     * Function to perform merge. Do not call it directly.
     *
     * @param string $x
     * @param string $y
     * @param string $z
     * @param mixed $key
     *
     * @return mixed
     * @throws Exception
     */
    protected function merge($x, $y, $z, $key)
    {
        // Convert the value into array.
        $ancestor = (strpos($x, "\n") !== false ? explode("\n", $x) : array($x));
        $local = (strpos($y, "\n") !== false ? explode("\n", $y) : array($y));
        $remote = (strpos($z, "\n") !== false ? explode("\n", $z) : array($z));

        // Count number of lines in value or elements in new formed array.
        $count_ancestor = count($ancestor);
        $count_remote = count($remote);
        $count_local = count($local);

        // If number of lines is different in all 3 values.
        // For example : addition in remote and removal in local node.
        //
        // Else get the value of number of lines in updated node.
        // For example : Addition of 2 lines in local.
        // Suppose: $count_ancestor = 2 and $count_remote = 2
        // then, $count_local would be 4 now.
        if ($count_ancestor != $count_local
            && $count_local!= $count_remote
            && $count_ancestor != $count_remote
        ) {
            $merged = $this->linesAddedRemovedAndModified(
                $ancestor,
                $local,
                $remote,
                $count_ancestor,
                $count_local,
                $count_remote
            );
        } else {
            // Store the updated count value in a variable $count.
            if ($count_ancestor == $count_local) {
                $count = $count_remote;
            } else {
                $count = $count_local;
            }
            // If $count > $count_ancestor, that means lines have been added.
            // Otherwise, lines has been removed or modified.
            if ($count > $count_ancestor) {
                 $merged = $this->linesAddedOrModified(
                     $ancestor,
                     $local,
                     $remote,
                     $count,
                     $count_remote,
                     $count_ancestor,
                     $count_local
                 );
            } else {
                 $merged = $this->linesRemovedOrModified(
                     $ancestor,
                     $local,
                     $remote,
                     $count_ancestor,
                     $count_local,
                     $count_remote
                 );
            }
        }
        // Convert returned array back to string.
        $merged[$key] = implode(PHP_EOL, $merged);
        return $merged[$key];
    }

    /**
     * Handles the addition of lines.
     *
     * @param array $ancestor
     * @param array $local
     * @param array $remote
     * @param $count
     * @param $count_remote
     * @param $count_ancestor
     * @param $count_local
     *
     * @return array
     * @throws Exception
     */
    protected function linesAddedOrModified(
        array $ancestor,
        array $local,
        array $remote,
        $count,
        $count_remote,
        $count_ancestor,
        $count_local
    ) {
        $merged = [];
        $counter = 0;
        // For all ancestors lines, it will check local and remote
        // and make sure only one of them has been edited.
        // Otherwise, throw an Exception.
        foreach ($ancestor as $key => $value) {
            $counter = $counter + 1;
            if ($ancestor[$key] == $local[$key]) {
                $merged[$key] = $remote[$key];
            } elseif ($ancestor[$key] == $remote[$key]
                || $local[$key] == $remote[$key]) {
                $merged[$key] = $local[$key];
            } else {
                throw new Exception("A conflict has occured");
            }
        }
        // Once done with ancestor lines, we have hunk of
        // lines added. We will add them as they are.
        // If the lines are added in both remote and local,
        // We will make sure they are same or throw exception.
        for ($i = $count_ancestor; $i < $count; $i++) {
            if ($count_ancestor == $count_remote) {
                $merged[$i] == $local[$i];
            } elseif ($count_ancestor == $count_local) {
                $merged[$i] = $remote[$i];
            } elseif ($count_local == $count_remote) {
                if ($local[$i] == $remote[$i]) {
                    $merged[$i] = $local[$i];
                } else {
                    throw new Exception("A conflict has occured");
                }
            }
        }
        return $merged;
    }

    /**
     * Handles removal or modification of lines
     *
     * @param array $ancestor
     * @param array $local
     * @param array $remote
     * @param int $count_ancestor
     * @param int $count_local
     * @param int $count_remote
     *
     * @return array
     * @throws Exception
     * @internal param int $count
     *
     */
    protected function linesRemovedOrModified(
        array $ancestor,
        array $local,
        array $remote,
        $count_ancestor,
        $count_local,
        $count_remote
    ) {
        $merged = [];
        $count_array = [$count_ancestor, $count_local, $count_remote];
        sort($count_array);
        $mincount = min($count_local, $count_ancestor, $count_remote);

        // First for loop compares all 3 nodes and returns updated node.
        for ($key = 0; $key < $mincount; $key++) {
            if ($ancestor[$key] == $local[$key]) {
                $merged[$key] = $remote[$key];
            } elseif ($ancestor[$key] == $remote[$key]
                || $local[$key] == $remote[$key]) {
                $merged[$key] = $local[$key];
            } else {
                throw new Exception("A conflict has occured");
            }
        }

        for ($key = $mincount; $key < $count_array[1]; $key++) {
            if ($mincount == $count_local && $ancestor[$key] != $remote[$key]) {
                throw new Exception("A whole new conflict arised");
            } elseif ($mincount == $count_remote
                && $ancestor[$key] != $local[$key]) {
                throw new Exception("A whole new conflict arised");
            }
        }
            return $merged;
    }

    /**
     * Method to deal with the case where Addition and removal.
     * takes place simuntaneously.
     * Example: 2 lines in ancestor, 3 lines in local.
     * and 1 line in remote.
     *
     * @param array $ancestor
     * @param array $local
     * @param array $remote
     * @param int   $count_ancestor
     * @param int   $count_local
     * @param int   $count_remote
     *
     * @return array
     * @throws Exception
     */
    protected function linesAddedRemovedAndModified(
        array $ancestor,
        array $local,
        array $remote,
        $count_ancestor,
        $count_local,
        $count_remote
    ) {
        $merged = [];
        $count_array = [$count_ancestor, $count_local, $count_remote];
        sort($count_array);
        $mincount = min($count_local, $count_ancestor, $count_remote);
        $maxcount = max($count_local, $count_ancestor, $count_remote);
        
        // First for loop compares all 3 nodes and returns updated node.
        for ($key = 0; $key < $mincount; $key++) {
            if ($ancestor[$key] == $local[$key]) {
                $merged[$key] = $remote[$key];
            } elseif ($ancestor[$key] == $remote[$key]
                || $local[$key] == $remote[$key]) {
                $merged[$key] = $local[$key];
            } else {
                throw new Exception("A conflict has occured");
            }
        }
        
        // Second for loop compares 2 nodes and if they are identical
        // add the changes to new array otherwise throw conflict exception.
        for ($key = $mincount; $key < $count_array[1]; $key++) {
            if ($count_ancestor == $mincount
                && ($count_remote == $maxcount || $count_local == $maxcount)
            ) {
                if ($local[$key] == $remote[$key]) {
                    if (!isset($ancestor[$key])) {
                        unset($merged[$key]);
                    }
                } else {
                    throw new Exception("A conflict has occured");
                }
            } elseif ($count_local == $mincount
                && ($count_ancestor == $maxcount
                    || $count_remote == $maxcount)
            ) {
                if ($ancestor[$key] == $remote[$key]) {
                    if (!isset($local[$key])) {
                        unset($merged[$key]);
                    }
                } else {
                    throw new Exception("A conflict has occured");
                }
            } elseif ($count_remote == $mincount
                && ($count_ancestor == $maxcount
                    || $count_local == $maxcount)) {
                if ($local[$key] == $ancestor[$key]) {
                    if (!isset($remote[$key])) {
                        unset($merged[$key]);
                    }
                } else {
                    throw new Exception("A conflict has occured");
                }
            }
        }

        // Third for loops just adds the added key to new array.
        for ($key = $count_array[1]; $key < $maxcount; $key++) {
            if ($count_remote == $maxcount) {
                $merged[$key] = $remote[$key];
            } elseif ($count_ancestor == $maxcount) {
                $merged[$key] = $ancestor[$key];
            } elseif ($count_local == $maxcount) {
                $merged[$key] = $local[$key];
            }
        }
        return $merged;
    }
}
