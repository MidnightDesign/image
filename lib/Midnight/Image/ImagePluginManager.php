<?php

namespace Midnight\Image;

use Exception;
use Midnight\Image\Filter\AbstractImageFilter;
use Zend\ServiceManager\AbstractPluginManager;

class ImagePluginManager extends AbstractPluginManager
{
    protected $invokableClasses = array(
        'fill' => 'Midnight\Image\Filter\Fill',
        'fit' => 'Midnight\Image\Filter\Fit',
        'type' => 'Midnight\Image\Info\Type',
    );

    public function validatePlugin($plugin)
    {
        if ($plugin instanceof AbstractImageFilter) {
            // we're okay
            return;
        }

        throw new Exception(sprintf(
            'Plugin of type %s is invalid; must implement %s\Helper\HelperInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}
