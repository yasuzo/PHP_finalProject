<?php

namespace Services;

class Normalizer{

    public function trimSpaces(string &$string): int{
        $regex = "/\s\s+/";
        $string = preg_replace($regex, ' ', $string, -1, $count);
        return $count;
    }


    public function removeLastSpace(string &$string): int{
        $regex = "/ $/";
        $string = preg_replace($regex, '', $string, -1, $count);
        return $count;
    }

    public function date_to_YMD(string &$string): int{
        $suffixRegex = '(?=\s|$|[\?\!\.\,])';
        $regex = '/(?<=\s|^)(0?[1-9]|[12]\d|3[01])\.(0?[1-9]|1[012])\.(\d{4})'.$suffixRegex.'/';
        $string = preg_replace($regex, '${3}-${2}-${1}', $string, -1, $count);
        return $count;
    }

    public function formatDate_YMD(string &$string): int{
        $suffixRegex = '(?=\s|$|[\?\!\.\,])';
        $regex = [
            '/(?<=^\d{4}\-)(?=[1-9]\-(?:[1-9]|[12]\d|3[01])'.$suffixRegex.')/',    // trazi '' nakon prve '-' u datumu gdje je samo jedna brojka
            '/(?<=^\d{4}\-(?:0[1-9]|1[012])\-)(?=[1-9]'.$suffixRegex.')/',     // trazi '' nakon druge '-' u datumu gdje je samo jedno brojka
            '/(?<=\s\d{4}\-)(?=[1-9]\-(?:[1-9]|[12]\d|3[01])'.$suffixRegex.')/',    // trazi '' nakon prve '-' u datumu gdje je samo jedna brojka
            '/(?<=\s\d{4}\-(?:0[1-9]|1[012])\-)(?=[1-9]'.$suffixRegex.')/'
        ]; 
        $replacements = ['0', '0', '0', '0'];
        $string = preg_replace($regex, $replacements, $string, -1, $count);
        return $count;
    }

    public function date_from_YMD_to_standard(string &$string): int{
        $prefixRegex = '(?<=^|\s)';
        $suffixRegex = '(?=\s|$|[\?\!\.\,])';
        $regex = '/'.$prefixRegex.'(\d{4})\-(0[1-9]|1[012])\-(0[1-9]|[12]\d|3[01])'.$suffixRegex.'/';
        $string = preg_replace($regex, '${3}.${2}.${1}', $string, -1, $count);
        return $count;
    }


    public function addSpaceAfter(string &$string, string $chars): int{
        $chars = preg_quote($chars, '/');
        $regex = "/(?<=[$chars])(?!\s)/";
        $string = preg_replace($regex, ' ', $string, -1, $count);
        return $count;
    }

    public function normalizeTelephoneNumber(string &$string): int{
        // radi za sve hrvatske pozivne brojeve
        $prefixRegex = '(?<=^|\s)((?>0|\+385)(?:1|2[0-3]|3[1-5]|4[02-47-9]|5[123]|976|9[125789]|6[012459]|7[24567]|80[01]))';

        /* $prefixRegex = '(?<=^| )((?>0|\+385)(?:1|2[0-3]|3[1-5]|4[02-47-9]|5[123]))'; */
        $suffixRegex = '(?=\s|$|[\?\!\.\,])';
        $regex = [
            '/'.$prefixRegex.'[\/\-\.]?(\d{4})[\/\-\.]?(\d{3})'.$suffixRegex.'/',
            '/'.$prefixRegex.'[\/\-\.]?(\d{3})[\/\-\.]?(\d)(\d{3})'.$suffixRegex.'/',
            '/'.$prefixRegex.'[\/\-\.]?(\d{3})[\/\-\.]?(\d{3})'.$suffixRegex.'/',
            '/'.$prefixRegex.'[\/\-\.]?(\d{2})[\/\-\.]?(\d)(\d)[\/\-\.]?(\d{2})'.$suffixRegex.'/',
            '/'.$prefixRegex.'[\/\-\.]?(\d{3})[\/\-\.]?(\d)(\d)[\/\-\.]?(\d{2})'.$suffixRegex.'/',
        ];

        $replacements = [
            '${1}/${2}-${3}',
            '${1}/${2}${3}-${4}',
            '${1}/${2}-${3}',
            '${1}/${2}${3}-${4}${5}',
            '${1}/${2}${3}-${4}${5}'
        ];

        $string = preg_replace($regex, $replacements, $string, -1, $count);

        return $count;
    }

    public function normalize(string &$string): int {
        $count = $this->trimSpaces($string);
        $count += $this->normalizeTelephoneNumber($string);
        $count += $this->date_to_YMD($string);
        $count += $this->formatDate_YMD($string);
        $count += $this->addSpaceAfter($string, '!?.,');
        $count += $this->date_from_YMD_to_standard($string);
        $count += $this->removeLastSpace($string);
        return $count;
    }

}