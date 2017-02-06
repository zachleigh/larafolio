<?php

use Illuminate\Support\Collection;

if (!Collection::hasMacro('objectIfEmpty')) {
    /*
     * Dump the arguments given followed by the collection.
     */
    Collection::macro('objectIfEmpty', function () {
        if ($this->isEmpty()) {
            return new \stdClass();
        }

        return $this;
    });
}
