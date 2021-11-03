<?php
/**
 * Namespace
 */
namespace Wsklad\Interfaces;

/**
 * Only WordPress
 */
defined('ABSPATH') || exit;

/**
 * Dependencies
 */
use Exception;

/**
 * Interface InterfaceSettings
 *
 * @package Wsklad\Interfaces
 */
interface InterfaceSettings
{
	/**
	 * Initializing
	 *
	 * @return void
	 * @throws Exception
	 */
	public function init();

	/**
	 * Get settings - all or single
	 *
	 * @param string $setting_key - optional
	 * @param mixed $default_return - default data, optional
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function get($setting_key = '', $default_return = '');

	/**
	 * Set settings - all or single
	 *
	 * @param mixed $setting_data - all data, or single
	 * @param string $setting_key - optional
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function set($setting_data = '', $setting_key = '');

	/**
	 * Save settings
	 *
	 * @param array $settings_data Data to save
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function save($settings_data = null);
}