<?php

namespace Model418\Core\Provider\FileProvider;

use Model418\Core\Provider\IProvider;
use Model418\Core\Provider\CacheDumpProvider;
use Model418\Core\Provider\TNamedIdProvider;
use Model418\Core\Request\FileRequestFactory;
use Model418\Core\Request\TextFileRequest;
use Model418\Core\Request\JSONFileRequest;
use Model418\Core\Request\YamlFileRequest;
use Model418\Core\Request\MarkdownFileRequest;

TextFileRequest::register();
JSONFileRequest::register();
YamlFileRequest::register();
MarkdownFileRequest::register();

class FileProvider extends CacheDumpProvider implements IProvider
{
    use TNamedIdProvider;


    /* FOLDER METHODS
     *************************************************************************/
    public function getFolder()
    {
        return $this->getKey();
    }
    
    public function setFolder($folder)
    {
        return $this->setKey($folder);
    }
    
    public function setKey($folder)
    {
        $folder = $this->validFolder($folder);
        parent::setKey($folder);
        return $this;
    }

    protected function validFolder($folder)
    {
        $realFolder = realpath($folder);
        if (!$realFolder) {
            throw new \RuntimeException('This data folder does not exist: ' . $folder);
        }
        return $realFolder;
    }


    /* FILE REQUEST METHODS
     *************************************************************************/
    public function setRequest($format)
    {
        parent::setRequest($this->getRequestFromName($format));
        return $this;
    }
    
    protected function initDefaultRequest()
    {
        return 'yml';
    }
    
    protected function getRequestFromName($format)
    {
        if (is_string($format)) {
            $format = (new FileRequestFactory)->get($format);
        }
        return $format;
    }
}