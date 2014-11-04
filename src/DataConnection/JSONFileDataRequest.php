<?php

namespace Elephant418\Model418\DataConnection;

class JSONFileDataRequest extends FileDataRequest
{

    /* ATTRIBUTES
     *************************************************************************/
    public static $extension = 'json';


    /* PROTECTED METHODS
     *************************************************************************/
    protected function getDataFromText($id, $text)
    {
        if (!$text) {
            return null;
        }
        $data = json_decode($text, true);
        if (!is_array($data)) {
            return null;
        }
        $data['id'] = $id;
        return $data;
    }
    
    protected function getTextFromData($id, $data)
    {
        return json_encode($data);
    }
}