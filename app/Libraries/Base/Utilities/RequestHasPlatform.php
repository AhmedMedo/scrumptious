<?php

namespace App\Libraries\Base\Utilities;

use Illuminate\Http\Request;

/**
 * Trait requestHasPlatform
 *
 * @package Telgani\Utilities
 */
trait RequestHasPlatform
{
    protected $platform = 'mobile';

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getPlatform(Request $request)
    {
        $this->platform = 'mobile';
        if ($request->headers->has('platform')) {
            $this->platform = strtolower($request->headers->get('platform')) === 'ios' ? 'ios' : $this->platform;
            $this->platform = strtolower($request->headers->get('platform')) === 'android' ? 'android' : $this->platform;
            $this->platform = strtolower($request->headers->get('platform')) === 'kiosk' ? 'kiosk' : $this->platform;
            $this->platform = strtolower($request->headers->get('platform')) === 'web' ? 'web' : $this->platform;
        }

        return $this->platform;
    }
}
