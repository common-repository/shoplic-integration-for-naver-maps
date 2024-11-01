<?php
/**
 * @var string $root_id
 * @var array  $extra_attrs
 */

use function Shoplic\NaverMap\isDevelopment;

if ( !defined('ABSPATH')) {
    exit;
}

if (empty ($root_id)) {
    wp_die('$root_id is not set');
}

if (empty($extra_attrs)) {
    $extra_attrs = [];
}

$attrs = ' ';
foreach ($extra_attrs as $key => $value) {
    $key = sanitize_key($key);
    if ($key) {
        $attrs .= $key . '="' . esc_attr($value) . '" ';
    }
}
$attrs = rtrim($attrs);
?>

<div id="<?php echo esc_attr($root_id); ?>" data-shoplic-naver-map-app-root="true"<?php echo $attrs; ?>>
    <noscript><?php esc_html_e('You need a browser that can run JavaScript.', 'shoplic-integration-for-naver-map'); ?></noscript>
    <?php if (isDevelopment()): ?>
        <p class="naver-map-develop-notice">
            <?php esc_html_e('Running in development mode. Maybe you have forgotten to execute <code style="background-color: #e0e0ee; border-radius: 4px; padding: 4px 8px;">yarn run dev</code>?', 'shoplic-integration-for-naver-map'); ?>
        </p>
    <?php endif ?>
</div>
