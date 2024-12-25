<?php

namespace App\Libraries\Base\Utilities;

use Illuminate\Http\Request;

trait RequestHasVersion
{
    protected $version = 0;

    /**
     * @param Request $request
     *
     * @return int|null|string|string[]
     */
    private function getVersion(Request $request)
    {
        $this->version = 0;
        if ($request->headers->has('version')) {
            $this->version = $request->headers->get('version');
        }

        return $this->version;
    }
}
