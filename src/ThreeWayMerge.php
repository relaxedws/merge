<?php

namespace Relaxed\Merge\ThreeWayMerge;

use Exception;

class ThreeWayMerge
{
    /**
     * Performs automatic merge or throws exception if no conflict arises.
     *
     * @param array $ancestor
     * @param array $local
     * @param array $remote
     * @return array
     * @throws Exception
     */
    public function performMerge(array $ancestor, array $local, array $remote)
    {
        // Returning a new Array for now. Can return the modified ancestor as well.
        $merged = $ancestor;
        $conflict = FALSE;
        foreach ($ancestor as $key=>$value) 
        {
            /* If ancestor's value is equal to remote's value
             * Set the merged array's value to local value
             */
            if ($value == $remote[$key]) {
                $merged[$key] = $local[$key];
            }
            /* If ancestor's value is equal to local's value
             * Set the merged array's value to remote value
             */
            elseif ($value == $local[$key]) {
                $merged[$key] = $remote[$key];
            }
            /* If local's value is equal to remote's value
             * Set the merged array's value to any value
             * as they both are same.
             */
            elseif ($remote[$key] == $local[$key]) {
                $merged[$key] = $local[$key];
            }
            /* If none of the above is True
             * Set conflict to TRUE
             */
            else {
                $conflict = TRUE;
            }
        }
        // Throw Exception if there is a conflict.
        if ($conflict) {
            throw new Exception('A merge conflict has occured.');
        }
        return $merged;
    }
}
