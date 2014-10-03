<?php

namespace Midnight\Image\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResponsiveImageFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ResponsiveImage
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ResponsiveImage('.', '.');
    }
}
