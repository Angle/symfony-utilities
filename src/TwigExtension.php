<?php

namespace Angle\SfUtilities;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('financial_format', [$this, 'financialFormat']),
            new TwigFilter('accounting_format', [$this, 'accountingFormat']),
            new TwigFilter('percentage_format', [$this, 'percentageFormat']),
            new TwigFilter('strpad', [$this, 'strpad']),
            new TwigFilter('trim', [$this, 'trim']),
            new TwigFilter('ltrim', [$this, 'ltrim']),
            new TwigFilter('rtrim', [$this, 'rtrim']),
            new TwigFilter('wordwrap', [$this, 'wordwrap'], ['is_safe' => ['html']]),
            new TwigFilter('camel2snake', [$this, 'camelCaseToSnakeCase']),
            new TwigFilter('filesize', [$this, 'humanFileSize']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('git_last_commit', [$this, 'gitLastCommit']),
            new TwigFunction('git_branch', [$this, 'gitBranch']),
        ];
    }

    // FILTERS
    public function financialFormat($number, $decimals = 2, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public function accountingFormat($number, $decimals = 2, $decPoint = '.', $thousandsSep = ',')
    {
        if ($number < 0) {
            $price = '(' . number_format(-1.0 * $number, $decimals, $decPoint, $thousandsSep) . ')';
        } else {
            $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        }

        return $price;
    }

    public function percentageFormat($number, $decimals = 1, $decPoint = '.', $thousandsSep = ',')
    {
        $percentage = number_format($number, $decimals, $decPoint, $thousandsSep);
        $percentage = $percentage.'%';

        return $percentage;
    }

    public function strpad($number, $pad_length, $pad_string)
    {
        return str_pad($number, $pad_length, $pad_string, STR_PAD_LEFT);
    }

    public function trim($str, $charlist=null) {
        if ($charlist !== null) {
            return trim($str, $charlist);
        } else {
            return trim($str);
        }
    }

    public function ltrim($str, $charlist=null)
    {
        if ($charlist !== null) {
            return ltrim($str, $charlist);
        } else {
            return ltrim($str);
        }
    }

    public function rtrim($str, $charlist=null)
    {
        if ($charlist !== null) {
            return rtrim($str, $charlist);
        } else {
            return rtrim($str);
        }
    }

    public function wordwrap($str, $width=75, $break="<br/>", $cut=false)
    {
        return wordwrap($str, $width, $break, $cut);
    }

    public function camelCaseToSnakeCase($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    public function humanFileSize($size, $precision = 2)
    {
        $units = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }
        return round($size, $precision).$units[$i];
    }


    // FUNCTIONS
    public function gitLastCommit(): string
    {
        // get current file dir
        $dir = dirname(__FILE__);
        $head = $dir . '/../../../.git/FETCH_HEAD'; // TODO: parametrize this..
        $head = realpath($head);

        if ($head === false) {
            return '';
        }

        $raw = file_get_contents($head);

        if ($raw === false) {
            return '';
        }

        // return the first 8 chars of the HEAD
        return substr($raw, 0, 8);
    }

    public function gitBranch(): string
    {
        // get current file dir
        $dir = dirname(__FILE__);
        $head = $dir . '/../../../.git/FETCH_HEAD'; // TODO: parametrize this..
        $head = realpath($head);

        if ($head === false) {
            return '';
        }

        $raw = file_get_contents($head);

        if ($raw === false) {
            return '';
        }

        // return the text between the single quotes in the FETCH_HEAD file
        // example:
        // a5817f29d01944eedbb6b5d9d81d89b530ece122		branch 'master' of https://github.com/MyOrg/MyRepo
        // should return: master
        if (preg_match("/'([^']+)'/", $raw, $matches)) {
            return $matches[1];
        } else {
            return '';
        }
    }
}