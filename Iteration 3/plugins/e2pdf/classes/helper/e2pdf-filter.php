<?php

/**
 * E2pdf Filter Helper
 * 
 * @copyright  Copyright 2017 https://e2pdf.com
 * @license    GPLv3
 * @version    1
 * @link       https://e2pdf.com
 * @since      1.07.09
 */
if (!defined('ABSPATH')) {
    die('Access denied.');
}

class Helper_E2pdf_Filter {

    public function is_stream($file_path) {
        if (strpos($file_path, '://') > 0) {
            $wrappers = array(
                'phar'
            );
            if (function_exists('stream_get_wrappers')) {
                $wrappers = stream_get_wrappers();
            }

            foreach ($wrappers as $wrapper) {
                if (in_array($wrapper, ['http', 'https', 'file'])) {
                    continue;
                }
                if (stripos($file_path, $wrapper . '://') === 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /*
     * Filter Unsupported HTML Tags
     */

    public function filter_html_tags($value) {

        if ($value) {
            $tags = array(
                'script', 'style'
            );
            foreach ($tags as $tag) {
                $value = preg_replace('#<' . $tag . '(.*?)>(.*?)</' . $tag . '>#is', '', $value);
            }
        }

        return $value;
    }

}
