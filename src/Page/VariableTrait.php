<?php
/*
 * Copyright (c) Arnaud Ligny <arnaud@ligny.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cecil\Page;

/**
 * Helper to set and get page variables.
 *
 * @property array properties
 */
trait VariableTrait
{
    abstract public function offsetExists($offset);
    abstract public function offsetGet($offset);
    abstract public function offsetSet($offset, $value);
    abstract public function offsetUnset($offset);

    /**
     * Set an array of variables.
     *
     * @param array $variables
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setVariables($variables)
    {
        if (!is_array($variables)) {
            throw new \Exception('Can\'t set variables: parameter is not an array');
        }
        foreach ($variables as $key => $value) {
            $this->setVariable($key, $value);
        }

        return $this;
    }

    /**
     * Get all variables.
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->properties;
    }

    /**
     * Set a variable.
     *
     * @param $name
     * @param $value
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setVariable($name, $value)
    {
        switch ($name) {
            case 'date':
                try {
                    if ($value instanceof \DateTime) {
                        $date = $value;
                    } else {
                        // timestamp
                        if (is_numeric($value)) {
                            $date = (new \DateTime())->setTimestamp($value);
                        } else {
                            // ie: 2019-01-01
                            if (is_string($value)) {
                                $date = new \DateTime($value);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    throw new \Exception(sprintf("Expected date string in page '%s'", $this->getId()));
                }
                $this->offsetSet('date', $date);
                break;
            case 'draft':
                if ($value === true) {
                    $this->offsetSet('published', false);
                }
                break;
            default:
                $this->offsetSet($name, $value);
        }

        return $this;
    }

    /**
     * Is variable exist?
     *
     * @param $name
     *
     * @return bool
     */
    public function hasVariable($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Get a variable.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function getVariable($name)
    {
        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        }
    }

    /**
     * Unset a variable.
     *
     * @param $name
     *
     * @return $this
     */
    public function unVariable($name)
    {
        if ($this->offsetExists($name)) {
            $this->offsetUnset($name);
        }

        return $this;
    }
}
