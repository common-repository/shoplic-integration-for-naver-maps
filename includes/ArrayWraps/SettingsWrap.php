<?php

namespace Shoplic\NaverMap\ArrayWraps;

class SettingsWrap implements ArrayWrap
{
    /** @var string */
    private $optionName;

    /** @var array */
    private $value;

    /** @var array */
    private $default;

    public function __construct(string $optionName, array $value, array $default)
    {
        $this->optionName = $optionName;
        $this->value      = $value;
        $this->default    = $default;
    }

    public function getClientId(): string
    {
        return $this->get('client_id', '');
    }

    public function getDisplayMethod(): string
    {
        return $this->get('display_method', 'manual');
    }

    public function getDisplayPosition(): string
    {
        return $this->get('display_position', 'bottom');
    }

    public function getDisplayPriority(): int
    {
        return (int)$this->get('display_priority', 10);
    }

    public function getLinkedPostTypes(): array
    {
        return (array)$this->get('linked_post_types', []);
    }

    public function getOptionName(): string
    {
        return $this->optionName;
    }

    public function isDevelopment(): bool
    {
        return (bool) $this->get('development', false);
    }

    /**
     * @param string $key
     * @param mixed  $fallback
     *
     * @return mixed
     */
    private function get(string $key, $fallback = null)
    {
        return $this->value[$key] ?? $this->default[$key] ?? $fallback;
    }
}
