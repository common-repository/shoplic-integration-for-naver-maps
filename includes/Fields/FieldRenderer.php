<?php
/**
 * @noinspection HtmlUnknownAttribute
 * @noinspection HtmlWrongAttributeValue
 */

namespace Shoplic\NaverMap\Fields;

class FieldRenderer
{
    public static function checkbox(array $args = [])
    {
        $args = wp_parse_args(
            $args,
            [
                'attrs'       => [],
                'description' => '',
                'instruction' => '',
            ]
        );

        $attrs = wp_parse_args(
            $args['attrs'] ?? [],
            [
                'id'    => '',
                'name'  => '',
                'value' => false,
            ]
        );

        printf(
            "<input id='%s' name='%s' type='checkbox' value='yes' %s>\n",
            esc_attr($attrs['id']),
            esc_attr($attrs['name']),
            checked($attrs['value'], true, false)
        );

        self::label($args['instruction'], $attrs['id']);
        self::description($args['description']);
    }

    public static function choose(array $args = [])
    {
        $args = wp_parse_args(
            $args,
            [
                'attrs'       => [],
                'description' => '',
                'options'     => [],
                'type'        => 'checkbox', // or 'radio'.
            ]
        );

        $attrs = wp_parse_args(
            $args['attrs'] ?? [],
            [
                'id'    => '',
                'name'  => '',
                'value' => '',
            ]
        );

        $type     = 'checkbox' == $args['type'] ? 'checkbox' : 'radio';
        $multiple = 'checkbox' == $type;

        $id       = esc_attr($attrs['id']);
        $name     = esc_attr($attrs['name']);
        $selected = $multiple ? (array)$attrs['value'] : (string)$attrs['value'];

        echo "\n<ul style='margin: 0'>\n";

        foreach ($args['options'] as $value => $label) {
            printf(
                "\t<li><input id='%s' type='%s' name='%s' value='%s' %s><label for='%s'>%s</label></li>\n",
                esc_attr("$id-$value"),
                esc_attr($type),
                esc_attr($name . ($multiple ? "[$value]" : '')),
                esc_attr($multiple ? 'yes' : $value),
                esc_attr(
                    $multiple ? checked(in_array($value, $selected), true, false) : checked($selected, $value, false)
                ),
                esc_attr("$id-$value"),
                self::filterText($label)
            );
        }

        echo "</ul>\n";

        self::description($args['description']);
    }

    public static function description(string $description): void
    {
        if ($description) {
            echo '<p class="description">' . self::filterText($description) . '</p>';
        }
    }

    public static function input(array $args = [])
    {
        $args = wp_parse_args(
            $args,
            [
                'attrs'       => [],
                'description' => '',
            ]
        );

        $attrs = wp_parse_args(
            $args['attrs'] ?? [],
            [
                'autocomplete' => 'on',
                'class'        => 'text',
                'id'           => '',
                'name'         => '',
                'value'        => '',
                'type'         => 'text',
                'placeholder'  => '',
            ]
        );

        printf(
            "<input autocomplete='%s' class='%s' id='%s' name='%s' type='%s' value='%s'>\n",
            esc_attr($attrs['autocomplete']),
            esc_attr($attrs['class']),
            esc_attr($attrs['id']),
            esc_attr($attrs['name']),
            esc_attr($attrs['type']),
            esc_attr($attrs['value'])
        );

        self::description($args['description']);
    }

    public static function label(string $instruction, string $for): void
    {
        if ($instruction) {
            echo '<label for="' . esc_attr($for) . '">' . self::filterText($instruction) . '</label>';
        }
    }

    public static function select(array $args = [])
    {
        $args = wp_parse_args(
            $args,
            [
                'attrs'       => [],
                'description' => '',
                'options'     => [],
            ]
        );

        $attrs = wp_parse_args(
            $args['attrs'] ?? [],
            [
                'id'    => '',
                'name'  => '',
                'value' => '',
            ]
        );

        $selected = $attrs['value'];

        printf("<select id='%s' name='%s'>\n", esc_attr($attrs['id']), esc_attr($attrs['name']));

        foreach ($args['options'] as $value => $label) {
            printf(
                "\t<option value='%s' %s>%s</option>\n",
                esc_attr($value),
                selected($selected, $value, false),
                esc_html($label)
            );
        }

        echo "</select>\n";

        self::description($args['description'] ?? '');
    }

    public static function filterText(string $text): string
    {
        static $allowed = [
            'a'    => [
                'class'  => true,
                'href'   => true,
                'style'  => true,
                'target' => true,
                'rel'    => true,
            ],
            'code' => [
                'class' => true,
                'style' => true,
            ],
            'pre'  => [
                'class' => true,
                'style' => true,
            ],
            'span' => [
                'class' => true,
                'style' => true,
            ],
        ];

        return wp_kses($text, $allowed);
    }
}
