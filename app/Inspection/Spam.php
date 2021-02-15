<?php

namespace App\Inspection;

/**
 * Class Spam
 * @package App\Inspection
 */
class Spam
{

    /**
     * All registered inspections
     * @var array
     */
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    /**
     * Detect spam
     *
     * @param $body
     * @return bool
     */
    public function detect($body)
    {
        forEach($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }



}
