<?php

namespace Shoplic\NaverMap\Fields;

class Meta
{
    /** @var string */
    protected $metaKey;

    /** @var string */
    protected $objectType;

    /** @var bool */
    protected $single;

    public function __construct($metaKey, $objectType, $single)
    {
        $this->metaKey    = $metaKey;
        $this->objectType = $objectType;
        $this->single     = $single;
    }

    /**
     * @param int   $objectId
     * @param mixed $value
     * @param bool  $unique
     *
     * @return int|false
     */
    public function add(int $objectId, $value, bool $unique = false)
    {
        return add_metadata($this->objectType, $objectId, $this->metaKey, $value, $unique);
    }

    /**
     * @param int $objectId
     *
     * @return mixed
     */
    public function get(int $objectId)
    {
        return get_metadata($this->objectType, $objectId, $this->metaKey, $this->single);
    }

    public function getKey(): string
    {
        return $this->metaKey;
    }

    /**
     * @param int   $objectId
     * @param mixed $value
     * @param mixed $prevValue
     *
     * @return bool|int
     */
    public function update(int $objectId, $value, $prevValue = '')
    {
        return update_metadata($this->objectType, $objectId, $this->metaKey, $value, $prevValue);
    }

    /**
     * @param int   $objectId
     * @param mixed $value
     *
     * @return bool
     */
    public function delete(int $objectId, $value = ''): bool
    {
        return delete_metadata($this->objectType, $objectId, $this->metaKey, $value);
    }
}
