<?php

/**
 * Gets the path to a versioned Mix file in a plugin.
 *
 * Use this function if you want to load plugin dependencies. This function will cache the contents
 * of the manifest files for you.
 *
 *
 * Inspired by <https://www.sitepoint.com/use-laravel-mix-non-laravel-projects/>.
 * https://github.com/mindkomm/theme-lib-mix/
 * @since 1.0.0
 *
 * @param string $path The relative path to the file.
 * @param string $args {
 *     Optional. An array of arguments for the function.
 *
  *     @type string $manifest_directory Custom relative path to manifest directory. Default `build`.
 * }
 *
 * @return string The versioned file URL.
 */

function mix($path, $pf = __FILE__, $args = [])
{
    // Manifest content cache.
    static $manifests = [];

    /**
     * Backwards compatibility.
     *
     * @todo Remove in 2.x
     */
    if (is_string($args)) {
        $args = [
            'manifest_directory' => $args,
        ];
    }

    $defaults = [
         'manifest_directory' => 'public',
         'hot_directory' => 'public'
    ];

    /**
     * Filters the default arguments used for the mix function
     *
     * @since 1.2.0
     *
     * @param array $defaults An array of default values.
     */
    $defaults = apply_filters('theme/mix/args/defaults', $defaults);

    $args = wp_parse_args($args, $defaults);

    $manifest_directory = $args['manifest_directory'];
    $hot_directory = $args['hot_directory'];

    $base_path = trailingslashit(plugin_dir_path(__FILE__));

    $manifest_path = $base_path . trailingslashit($manifest_directory) . 'mix-manifest.json';

    $hot_file = $base_path.trailingslashit($hot_directory).'hot';

    if(file_exists($hot_file)){
        $url = file_get_contents($hot_file);
        return $url.$path;
    }

    // Bailout if manifest couldn’t be found.
    if (!file_exists($manifest_path)) {
        return $base_path . $path;
    }

    if (!isset($manifests[$manifest_path])) {
        // @codingStandardsIgnoreLine
        $manifests[$manifest_path] = json_decode(file_get_contents($manifest_path), true);
    }

    $manifest = $manifests[$manifest_path];

    // Remove manifest directory from path.
    $path = str_replace($manifest_directory, '', $path);
    // Make sure there’s a leading slash.
    $path = '/' . ltrim($path, '/');

    // Bailout with default plugin path if file could not be found in manifest.
    if (!array_key_exists($path, $manifest)) {

        return $base_path. $path;;
    }

    // Get file URL from manifest file.
    $path = $manifest[$path];
    // Make sure there’s no leading slash.
    $path = ltrim($path, '/');

    return plugins_url(trailingslashit($manifest_directory) . $path, $pf);
}
