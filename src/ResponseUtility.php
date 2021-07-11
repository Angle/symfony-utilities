<?php

namespace Angle\SfUtilities;

use Symfony\Component\HttpFoundation\Response;

abstract class ResponseUtility
{
    /**
     * Returns a string with the "rebuilt" headers
     * @param Response $response
     * @return string
     */
    public static function headersAsString(Response $response): string
    {
        $s = '';

        foreach ($response->headers->all() as $key => $values) {
            $s .= $key . ': ' . implode(';', $values) . PHP_EOL;
        }

        return $s;
    }
}