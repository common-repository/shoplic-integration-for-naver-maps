<?php

namespace Shoplic\NaverMap\ArrayWraps;

class MapAttsWrap implements ArrayWrap
{
    /** @var array */
    private $atts;

    public function __construct($atts)
    {
        $this->atts = wp_parse_args($atts, self::getDefautlAtts());

        if (is_bool($this->atts['disabled'])) {
            $this->atts['disabled'] = $this->atts['disabled'] ? 'yes' : 'no';
        }

        if (is_int($this->atts['height'])) {
            $this->atts['height'] = $this->atts['height'] . 'px';
        }

        if ( ! is_int($this->atts['post_id'])) {
            $this->atts['post_id'] = (int)$this->atts['post_id'];
        }

        if (is_int($this->atts['width'])) {
            $this->atts['width'] = $this->atts['width'] . 'px';
        }

        if ( ! is_int($this->atts['zoom'])) {
            $this->atts['zoom'] = (int)$this->atts['zoom'];
        }
    }

    public function getAtts(): array
    {
        return $this->atts;
    }

    public function getDisabled(): string
    {
        return $this->atts['disabled'];
    }

    public function getHeight(): string
    {
        return $this->atts['height'];
    }

    public function getPostId(): int
    {
        return $this->atts['post_id'];
    }

    public function getWidth(): string
    {
        return $this->atts['width'];
    }

    public function getZoom(): int
    {
        return $this->atts['zoom'];
    }

    public static function getDefautlAtts(): array
    {
        return [
            'disabled' => 'no',
            'height'   => '240px',
            'post_id'  => 0,
            'width'    => '100%',
            'zoom'     => 16,
        ];
    }

    public static function create($atts): MapAttsWrap
    {
        return new static($atts);
    }
}
