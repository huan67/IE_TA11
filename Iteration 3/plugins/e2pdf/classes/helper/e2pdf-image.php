<?php

/**
 * E2pdf Image Helper
 * 
 * @copyright  Copyright 2017 https://e2pdf.com
 * @license    GPLv3
 * @version    1
 * @link       https://e2pdf.com
 * @since      0.00.01
 */
if (!defined('ABSPATH')) {
    die('Access denied.');
}

class Helper_E2pdf_Image {

    private $allowed_ext = array(
        'jpg', 'jpeg', 'png', 'gif', 'tif', 'bmp'
    );
    private $helper;

    public function __construct() {
        $this->helper = Helper_E2pdf_Helper::instance();
    }

    /**
     * Get Base64 Encoded Image
     * 
     * @param string $image - Image path
     * 
     * @return mixed - Base64 encoded image OR FALSE
     */
    public function get_image($image) {

        $source = false;

        if ($image) {

            preg_match('/src=(?:"|\')([^"\']*)(?:"|\')/', $image, $matches);
            if (isset($matches[1])) {
                $image = $matches[1];
            }

            $image = trim($image);
            $site_url = site_url('/');
            $https = str_replace('http://', 'https://', $site_url);
            $http = str_replace('https://', 'http://', $site_url);
            if (!get_option('e2pdf_images_remote_request')) {
                $image_path = false;
                if (0 === strpos($image, $https)) {
                    $image_path = ABSPATH . substr($image, strlen($https));
                } elseif (0 === strpos($image, $http)) {
                    $image_path = ABSPATH . substr($image, strlen($http));
                }
                if ($image_path && @file_exists($image_path) && in_array(strtolower(pathinfo($image_path, PATHINFO_EXTENSION)), $this->allowed_ext)) {
                    $source = base64_encode(file_get_contents($image_path));
                }
            }

            if (!$source) {
                if (0 === strpos($image, ABSPATH) && @file_exists($image) && in_array(strtolower(pathinfo($image, PATHINFO_EXTENSION)), $this->allowed_ext)) {
                    $source = base64_encode(file_get_contents($image));
                } elseif (0 === strpos($image, 'data:image/')) {
                    $image = preg_replace('/^data:image\/[^;]+;base64,/', '', $image);
                    $tmp_image = base64_decode($image, true);
                    if ($this->get_image_extension($tmp_image)) {
                        $source = $image;
                    }
                } elseif ($tmp_image = base64_decode($image, true)) {
                    if ($this->get_image_extension($tmp_image)) {
                        $source = $image;
                    }
                } elseif ($body = $this->get_by_url($image)) {
                    $source = base64_encode($body);
                }
            }
        }
        return $source;
    }

    /**
     * Get image by Url
     * 
     * @param string $url - Url to image
     * 
     * @return array();
     */
    public function get_by_url($url) {

        $timeout = get_option('e2pdf_images_timeout');
        if ($timeout === false) {
            $timeout = 30;
        }

        $response = wp_remote_get($url, array(
            'timeout' => $timeout,
            'sslverify' => false
        ));

        if (wp_remote_retrieve_response_code($response) === 200) {
            return wp_remote_retrieve_body($response);
        } else {
            return '';
        }
    }

    public function get_image_extension($image = false) {

        if (!$image) {
            return false;
        }

        $mime = false;
        if (function_exists('finfo_open') && function_exists('finfo_buffer')) {
            $f = finfo_open();
            $mime = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
        } elseif (function_exists('image_type_to_mime_type') && function_exists('getimagesizefromstring')) {
            $size = getimagesizefromstring($image);
            if (isset($size['mime'])) {
                $mime = $size['mime'];
            }
        }

        switch ($mime) {
            case 'image/jpeg':
                $ext = "jpg";
                break;

            case 'image/png':
                $ext = "png";
                break;

            default:
                $ext = false;
                break;
        }

        return $ext;
    }

}
