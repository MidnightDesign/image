<?php

namespace Midnight\Image\Plugin;

use Midnight\Image\Filter\Contain;
use Midnight\Image\Filter\Cover;
use Midnight\Image\View\Helper\ResponsiveImageFactory;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

/**
 * Class PluginManager
 *
 * @package Midnight\Image\Plugin
 * @method PluginInterface get($name)
 */
class PluginManager extends AbstractPluginManager
{
    public function __construct()
    {
        // Add bundled plugins
        $this->setInvokableClass('contain', Contain::class);
        $this->setInvokableClass('cover', Cover::class);
    }

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof PluginInterface) {
            return;
        }
        throw new Exception\RuntimeException(sprintf(
            'Image plugins must implement %s.',
            PluginInterface::class
        ));
    }
}
