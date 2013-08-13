<?php

namespace Midnight\Image;

class Image implements ImageInterface
{
    private $file;

    private function __construct($file)
    {
        $this->file = $file;
    }

    public static function open($file)
    {
        return new self($file);
    }

    public function getFile()
    {
        return $this->file;
    }

}