<?php
/**
 * 관리자 설정 템플릿
 *
 * 가로 배너 사이즈 권장: 1000*150
 * 세로 배너 사이즈 권장:  120*630
 *
 * @var string $banner_horizontal
 * @var string $banner_url
 * @var string $banner_vertical
 * @var string $option_group
 * @var string $page
 */

if ( !defined('ABSPATH')) {
    exit;
}

if ( ! isset($banner_horizontal)) {
    $banner_horizontal = '';
}

if ( ! isset($banner_url)) {
    $banner_url = '';
}

if ( ! isset($banner_vertical)) {
    $banner_vertical = '';
}
?>
<div class="settings-wrap">
    <form method="post" action="<?php echo esc_url(admin_url('options.php')); ?>">
        <?php
        settings_fields($option_group);
        do_settings_sections($page);
        submit_button();
        ?>
    </form>
    <div class="banner-wrap">
        <div class="banner">
            <?php if ($banner_horizontal && $banner_url) : ?>
                <a href="<?php echo esc_url($banner_url); ?>">
                    <img alt="<?php esc_attr_e('Horizontal banner', 'shoplic-integration-for-naver-map'); ?>"
                         class="banner-horizontal"
                         src="<?php echo esc_url($banner_horizontal); ?>">
                </a>
            <?php endif; ?>
            <?php if ($banner_vertical && $banner_url) : ?>
                <a href="<?php echo esc_url($banner_url); ?>">
                    <img alt="<?php esc_attr_e('Vertical banner', 'shoplic-integration-for-naver-map'); ?>"
                         class="banner-vertical"
                         src="<?php echo esc_url($banner_vertical); ?>">
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
