<?php

namespace Shoplic\NaverMap\Fields;

class Option
{
    /** @var string */
    protected $optionName;

    /** @var bool */
    protected $autoload;

    public function __construct(string $optionName, bool $autoload)
    {
        $this->optionName = $optionName;
        $this->autoload   = $autoload;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function add($value): bool
    {
        return add_option($this->optionName, $value, '', $this->autoload);
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return delete_option($this->optionName);
    }

    /**
     * @param bool $defaultValue
     *
     * @return mixed
     */
    public function get(bool $defaultValue = false)
    {
        return get_option($this->optionName, $defaultValue);
    }

    public function getKey(): string
    {
        return $this->optionName;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function update($value): bool
    {
        return update_option($this->optionName, $value, $this->autoload);
    }
}