<?php

namespace Spy\Timeline;

/**
 * Metadata
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Metadata
{
    /**
     * @var array
     */
    protected $classes = array();

    /**
     * @param string $name  name
     * @param string $value value
     *
     * @return Metadata
     */
    public function setClass($name, $value)
    {
        $this->classes[$name] = $value;

        return $this;
    }

    /**
     * @param string $name name
     *
     * @return string
     */
    public function getClass($name)
    {
        if (!isset($this->classes[$name])) {
            throw new \InvalidArgumentException(sprintf('class "%s" is not defined on metadatas', $name));
        }

        return $this->classes[$name];
    }
}
