<?php

namespace Angle\SfUtilities;

use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class MimeTypeUtility
{
    /**
     * Check if the file is a valid PDF file from its MIME Type
     * @param UploadedFile $file
     * @return bool
     */
    public static function validPDF(UploadedFile $file)
    {
        return in_array($file->getClientMimeType(), ['application/pdf']);
    }

    /**
     * Check if the file is a valid XML file from its MIME Type
     * @param UploadedFile $file
     * @return bool
     */
    public static function validXML(UploadedFile $file)
    {
        return in_array($file->getClientMimeType(), ['application/xml', 'text/xml']);
    }
}