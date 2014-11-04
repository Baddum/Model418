<?php

namespace Elephant418\Model418\DataConnection;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class YamlFileDataRequest extends FileDataRequest
{

    /* ATTRIBUTES
     *************************************************************************/
    public static $extension = 'yml';


    /* PROTECTED METHODS
     *************************************************************************/
    protected function getDataFromText($text)
    {
        try {
            $data = Yaml::parse($text);
        } catch (ParseException $e) {
            return null;
        }
        return $data;
    }
    
    protected function getTextFromData($data)
    {
        return Yaml::dump($data, 3);
    }
}