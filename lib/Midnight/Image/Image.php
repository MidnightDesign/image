<?php

namespace Midnight\Image;

use Exception;
use Midnight\Image\Filter\AbstractImageFilter;

class Image implements ImageInterface
{
    private $file;
    /**
     * @var array Cache for the plugin call
     */
    private $__pluginCache = array();
    /**
     * Helper plugin manager
     *
     * @var ImagePluginManager
     */
    private $__helpers;

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

    /**
     * Overloading: proxy to helpers
     *
     * Proxies to the attached plugin manager to retrieve, return, and potentially
     * execute helpers.
     *
     * * If the helper does not define __invoke, it will be returned
     * * If the helper does define __invoke, it will be called as a functor
     *
     * @param  string $method
     * @param  array $argv
     * @return mixed
     */
    public function __call($method, $argv)
    {
        if (!isset($this->__pluginCache[$method])) {
            $this->__pluginCache[$method] = $this->plugin($method);
        }
        if (is_callable($this->__pluginCache[$method])) {
            return call_user_func_array($this->__pluginCache[$method], $argv);
        }
        return $this->__pluginCache[$method];
    }

    /**
     * Get plugin instance
     *
     * @param  string $name Name of plugin to return
     * @param  null|array $options Options to pass to plugin constructor (if not already instantiated)
     * @return AbstractImageFilter
     */
    public function plugin($name, array $options = null)
    {
        return $this->getHelperPluginManager()->get($name, $options);
    }

    /**
     * Get helper plugin manager instance
     *
     * @return ImagePluginManager
     */
    public function getHelperPluginManager()
    {
        if (null === $this->__helpers) {
            $this->setHelperPluginManager(new ImagePluginManager());
        }
        return $this->__helpers;
    }

    /**
     * Set helper plugin manager instance
     *
     * @param  string|ImagePluginManager $helpers
     * @return Image
     * @throws Exception
     */
    public function setHelperPluginManager($helpers)
    {
        if (is_string($helpers)) {
            if (!class_exists($helpers)) {
                throw new Exception(sprintf(
                    'Invalid helper helpers class provided (%s)',
                    $helpers
                ));
            }
            $helpers = new $helpers();
        }
        if (!$helpers instanceof ImagePluginManager) {
            throw new Exception(sprintf(
                'Helper helpers must extend Zend\View\HelperPluginManager; got type "%s" instead',
                (is_object($helpers) ? get_class($helpers) : gettype($helpers))
            ));
        }
        $this->__helpers = $helpers;

        return $this;
    }

}