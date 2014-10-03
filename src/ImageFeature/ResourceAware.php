<?php

namespace Midnight\Image\ImageFeature;

interface ResourceAware
{
    /**
     * @return resource
     */
    public function getResource();
} 
