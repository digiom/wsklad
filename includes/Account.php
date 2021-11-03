<?php
/**
 * Namespace
 */
namespace Wsklad;

/**
 * Only WordPress
 */
defined('ABSPATH') || exit;

/**
 * Dependencies
 */
use Exception;
use Wsklad\Data\Storage;
use Wsklad\Data\Entities\DataAccounts;

/**
 * Class Account
 *
 * @package Wsklad\Data
 */
class Account extends DataAccounts
{
	/**
	 * Default data
	 *
	 * @var array
	 */
	protected $data =
	[
		'name' => '',
		'status' => 'draft',
		'options' => [],
		'date_create' => null,
		'date_modify' => null,
		'date_activity' => null,
	];

	/**
	 * Account constructor.
	 *
	 * @param int $account
	 *
	 * @throws Exception
	 */
	public function __construct($account = 0)
	{
		parent::__construct();

		if(is_numeric($account) && $account > 0)
		{
			$this->set_id($account);
		}
		elseif($account instanceof self)
		{
			$this->set_id(absint($account->get_id()));
		}
		else
		{
			$this->set_object_read(true);
		}

		$this->storage = Storage::load($this->object_type);

		if($this->get_id() > 0)
		{
			$this->storage->read($this);
		}
	}

	/**
	 * Get name
	 *
	 * @param string $context What the value is for. Valid values are view and edit
	 *
	 * @return string
	 */
	public function get_name($context = 'view')
	{
		return $this->get_prop('name', $context);
	}

	/**
	 * Set name
	 *
	 * @param string $name name
	 */
	public function set_name($name)
	{
		$this->set_prop('name', $name);
	}

	/**
	 * Get status
	 *
	 * @param string $context What the value is for. Valid values are view and edit
	 *
	 * @return string
	 */
	public function get_status($context = 'view')
	{
		return $this->get_prop('status', $context);
	}

	/**
	 * Set status
	 *
	 * @param string $name status
	 */
	public function set_status($name)
	{
		$this->set_prop('status', $name);
	}

	/**
	 * Get options
	 *
	 * @param string $context What the value is for. Valid values are view and edit
	 *
	 * @return array
	 */
	public function get_options($context = 'view')
	{
		return $this->get_prop('options', $context);
	}

	/**
	 * Set options
	 *
	 * @param array $name options
	 */
	public function set_options($name)
	{
		$this->set_prop('options', $name);
	}

	/**
	 * Get created date
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return Datetime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_create($context = 'view')
	{
		return $this->get_prop('date_create', $context);
	}

	/**
	 * Get modified date
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return Datetime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_modify($context = 'view')
	{
		return $this->get_prop('date_modify', $context);
	}

	/**
	 * Get activity date
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return Datetime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_activity($context = 'view')
	{
		return $this->get_prop('date_activity', $context);
	}

	/**
	 * Set created date
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime.
	 * If the DateTime string has no timezone or
	 * offset, WordPress site timezone will be assumed. Null if their is no date.
	 *
	 * @throws Exception
	 */
	public function set_date_create($date = null)
	{
		$this->set_date_prop('date_create', $date);
	}

	/**
	 * Set modified date
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime.
	 * If the DateTime string has no timezone or
	 * offset, WordPress site timezone will be assumed. Null if their is no date.
	 *
	 * @throws Exception
	 */
	public function set_date_modify($date = null)
	{
		$this->set_date_prop('date_modify', $date);
	}

	/**
	 * Set activity date
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime.
	 * If the DateTime string has no timezone or
	 * offset, WordPress site timezone will be assumed. Null if their is no date.
	 *
	 * @throws Exception
	 */
	public function set_date_activity($date = null)
	{
		$this->set_date_prop('date_activity', $date);
	}

	/**
	 * Returns if configuration is active.
	 *
	 * @return bool True if validation passes.
	 */
	public function is_active()
	{
		return $this->is_status('active');
	}

	/**
	 * Returns if configuration is inactive.
	 *
	 * @return bool True if validation passes.
	 */
	public function is_inactive()
	{
		return $this->is_status('inactive');
	}

	/**
	 * Returns if configuration is status.
	 *
	 * @param string $status
	 *
	 * @return bool True if validation passes.
	 */
	public function is_status($status = 'active')
	{
		return $status === $this->get_status();
	}
}