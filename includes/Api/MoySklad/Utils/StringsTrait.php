<?php
/**
 * Namespace
 */
namespace Wsklad\Api\MoySklad\Utils;

/**
 * Only WordPress
 */
defined('ABSPATH') || exit;

/**
 * Trait StringsTrait
 *
 * @package Wsklad\Api\MoySklad\Utils
 */
trait StringsTrait
{
    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    public function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if($length === 0)
        {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
}
