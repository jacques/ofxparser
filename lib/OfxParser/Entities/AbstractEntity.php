<?php declare(strict_types=1);

namespace OfxParser\Entities;

abstract class AbstractEntity
{
    /**
     * Allow functions to be called as properties
     * to unify the API
     *
     * @param string $name
     * @return mixed|bool
     */
    public function __get(string $name)
    {
        if (method_exists($this, lcfirst($name))) {
            return $this->{$name}();
        }
        return false;
    }
}
