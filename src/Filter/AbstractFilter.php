<?php

namespace Midnight\Image\Filter;

abstract class AbstractFilter implements FilterInterface
{
    public function __invoke()
    {
        if (!method_exists($this, 'filter')) {
            throw new \RuntimeException();
        }
        return call_user_func_array([$this, 'filter'], func_get_args());
    }
} 
