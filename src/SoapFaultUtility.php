<?php

namespace Angle\SfUtilities;

use Symfony\Component\HttpFoundation\Response;

abstract class SoapFaultUtility
{
    public static function parseXml(string $xmlString)
    {
        $xml = simplexml_load_string($xmlString);

        if ($xml === false) {
            // could not parse the XML
            return null;
        }

        return $xml->children('SOAP-ENV', true);
    }

    /**
     * Create a valid SOAP Fault XML Response
     * @param string $code
     * @param string $string
     * @return Response
     */
    public static function createSoapFaultResponse(string $code, string $string): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        $content = self::SOAP_FAULT;
        $content = str_replace('{{faultcode}}', $code, $content);
        $content = str_replace('{{faultstring}}', $string, $content);

        $response->setContent($content);

        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    const SOAP_FAULT = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
    <SOAP-ENV:Body>
        <SOAP-ENV:Fault>
            <faultcode>{{faultcode}}</faultcode>
            <faultstring>{{faultstring}}</faultstring>
        </SOAP-ENV:Fault>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
XML;

}