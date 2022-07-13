<?php

namespace Brezgalov\ActivityId;

class ActivityId
{
    const DEFAULT_SEPARATOR = '/';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $unique;

    /**
     * @var bool
     */
    protected $moreEntropy;

    /**
     * @var string
     */
    protected $activityIdBuilt;

    /**
     * @var string
     */
    protected $partsSeparator;

    /**
     * ActivityId constructor.
     * @param string|null $name
     * @param bool $unique
     * @param false $moreEntropy
     * @param string $partsSeparator
     */
    public function __construct($name = null, $unique = true, $moreEntropy = false, $partsSeparator = self::DEFAULT_SEPARATOR)
    {
        $this->partsSeparator = $partsSeparator;
        $this->name = $name;
        $this->unique = $unique;
        $this->moreEntropy = $moreEntropy;
    }

    /**
     * @return string
     */
    public function build()
    {
        $time = (string)microtime(1);

        $this->activityIdBuilt = $time;

        if ($this->name) {
            $this->activityIdBuilt = "{$this->name}{$this->partsSeparator}{$this->activityIdBuilt}";
        }

        if ($this->unique) {
            $this->activityIdBuilt = uniqid("{$this->activityIdBuilt}{$this->partsSeparator}", $this->moreEntropy);
        }

        return $this->activityIdBuilt;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->activityIdBuilt ?: $this->build();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}