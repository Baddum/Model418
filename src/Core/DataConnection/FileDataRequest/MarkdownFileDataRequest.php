<?php

namespace Elephant418\Model418\Core\DataConnection\FileDataRequest;

use \Michelf\MarkdownExtra;
use \Markdownify\ConverterExtra as MarkdownifyExtra;

class MarkdownFileDataRequest extends FileDataRequest
{

    /* ATTRIBUTES
     *************************************************************************/
    public static $extension = 'md';
    public static $factoryIndexList = array('markdown', 'md');


    /* PROTECTED METHODS
     *************************************************************************/
    protected function getDataFromText($text)
    {
        return MarkdownExtra::defaultTransform($text);
    }
    
    protected function getTextFromData($data)
    {
        if (!is_string($data)) {
            throw new \RuntimeException('Wrong type of data to markdownify: '.get_type($data));
        }
        return (new MarkdownifyExtra)->parseString($data);
    }
}