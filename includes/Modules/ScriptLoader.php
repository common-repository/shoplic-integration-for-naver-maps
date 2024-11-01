<?php

namespace Shoplic\NaverMap\Modules;

final class ScriptLoader implements Module
{
    /**
     * Naver maps API URL.
     *
     * @see readme.txt
     */
    private const MAP_URL = 'https://oapi.map.naver.com/openapi/v3/maps.js';

    /** @var string[] */
    private $handles = [];

    /** @var array */
    private $manifest = [];

    public function __construct()
    {
        $manifestPath = dirname(NM_MAIN) . '/dist/manifest.json';

        if (file_exists($manifestPath) && is_file($manifestPath) && is_readable($manifestPath)) {
            $this->manifest = json_decode(file_get_contents($manifestPath), true) ?: [];
        }

        wp_register_script(
            'nm-dev-refresh',
            plugins_url('src/refresh.js', NM_MAIN),
            [],
            NM_VERSION,
            [
                'in_footer' => true,
                'strategy'  => 'defer',
            ]
        );
        $this->handles['nm-dev-refresh'] = true;

        wp_register_script(
            'nm-dev-vite-client',
            self::getDevServerUrl() . '@vite/client',
            ['nm-dev-refresh'],
            null,
            [
                'in_footer' => true,
                'strategy'  => 'defer',
            ]
        );
        $this->handles['nm-dev-vite-client'] = true;

        wp_register_script(
            'nm-naver-map',
            add_query_arg(
                [
                    'ncpClientId' => nm()->settings->wrap->getClientId(),
                ],
                self::MAP_URL
            ),
            [],
            null,
            ['in_footer' => true]
        );

        wp_register_script(
            'nm-naver-map-geocoder',
            add_query_arg(
                [
                    'ncpClientId' => nm()->settings->wrap->getClientId(),
                    'submodules'  => 'geocoder',
                ],
                self::MAP_URL
            ),
            [],
            null,
            ['in_footer' => true]
        );

        add_filter('script_loader_tag', [$this, 'filterChangeType'], 999, 3);
    }

    public function enqueueScript(string $entry, array $deps = [], bool $isDevelopment = false): self
    {
        if ( ! isset($this->handles[$entry])) {
            if ($isDevelopment) {
                $this->developEnqueue($entry, $deps);
            } else {
                $this->productionEnqueue($entry, $deps);
            }

            $this->handles[$entry] = true;
        }

        return $this;
    }

    public function localizeScript(string $handle, string $objectName, array $value): self
    {
        wp_localize_script($handle, $objectName, $value);

        return $this;
    }

    public function filterChangeType(string $tag, string $handle): string
    {
        if (isset($this->handles[$handle])) {
            // <script> tag can be found more than once if wp_add_inline_script() is called.
            $lastPos = 0;
            $scripts = [];

            do {
                $pos = strpos($tag, '<script ', $lastPos + 1);
                if ($pos > $lastPos) {
                    $scripts[] = trim(substr($tag, $lastPos, $pos - $lastPos));
                    $lastPos   = $pos;
                }
            } while ($pos !== false);

            $rest = trim(substr($tag, $lastPos));
            if (str_starts_with($rest, '<script')) {
                $scripts[] = trim($rest);
                $rest      = '';
            }

            foreach ($scripts as &$script) {
                if (str_starts_with($script, '<script ')) {
                    $attrs = substr($script, 6, strpos($script, '>') - 6);
                    if ( ! str_contains($attrs, 'src=')) {
                        continue;
                    }

                    $replace = '<script ';
                    $type    = false;

                    preg_match_all(
                        '/(\w+)=["\']?((?:.(?!["\']?\s+\S+=|\s*\/?[>"\']))+.)["\']?/',
                        $attrs,
                        $matches,
                        PREG_SET_ORDER
                    );

                    foreach ($matches as $match) {
                        if ('type' === $match[1]) {
                            $replace .= " type='module'";
                            $type    = true;
                        } else {
                            $replace .= " $match[0]";
                        }
                    }

                    if ( ! $type) {
                        $replace .= " type='module'";
                    }

                    $replace .= '></script>' . PHP_EOL;

                    $script = $replace;
                }
            }

            $tag = implode(PHP_EOL, $scripts) . $rest . PHP_EOL;
        }

        return $tag;
    }

    protected function developEnqueue(string $entry, array $deps): void
    {
        wp_enqueue_script(
            $entry,
            $this->getDevServerUrl() . "src/$entry",
            array_merge(
                [
                    'wp-i18n',
                    'nm-dev-vite-client'
                ],
                $deps
            ),
            null,
            ['in_footer' => true]
        );

        wp_add_inline_script(
            $entry,
            "console.info('$entry is running in development mode.')"
        );
    }

    protected function productionEnqueue(string $entry, array $deps): void
    {
        $key     = "src/$entry";
        $isEntry = $this->manifest[$key]['isEntry'] ?? false;
        $src     = $isEntry ? $this->manifest[$key]['file'] : '';
        $css     = $isEntry && isset($this->manifest[$key]['css']) ? $this->manifest[$key]['css'] : [];

        if ($src) {
            wp_enqueue_script(
                $entry,
                plugins_url("dist/$src", NM_MAIN),
                $deps,
                null,
                ['in_footer' => true]
            );
        }

        foreach ($css as $idx => $item) {
            wp_enqueue_style(
                "$entry-$idx",
                plugins_url("dist/$item", NM_MAIN),
                [],
                null
            );
        }

        wp_set_script_translations(
            $entry,
            'shoplic-integration-for-naver-map',
            plugin_dir_path(NM_MAIN) . 'languages'
        );
    }

    protected static function getDevServerUrl(bool $preview = false): string
    {
        return trailingslashit('http://localhost:' . ($preview ? '4173' : '5173'));
    }
}
