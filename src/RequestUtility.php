<?php

namespace Angle\SfUtilities;

use Symfony\Component\HttpFoundation\Request;

abstract class RequestUtility
{
    /**
     * Returns a string with the "rebuilt" headers
     * @param Request $request
     * @return string
     */
    public static function headersAsString(Request $request): string
    {
        $s = '';

        foreach ($request->headers->all() as $key => $values) {
            $s .= $key . ': ' . implode(';', $values) . PHP_EOL;
        }

        return $s;
    }
}