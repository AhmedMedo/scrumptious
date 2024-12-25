<?php

namespace App\Libraries\Support;

trait HasRequirements
{
    /**
     * @param array $requirements
     *
     * @return bool
     */
    protected function meetRequirements(array $requirements): bool
    {
        return count(array_filter($requirements)) === count($requirements);
    }
}
