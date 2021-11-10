<?php
/**
 * Namespace
 */
namespace Wsklad\Admin\Accounts;

/**
 * Only WordPress
 */
defined('ABSPATH') || exit;

/**
 * Dependencies
 */
use Exception;
use Wsklad\Abstracts\FormAbstract;
use Wsklad\Account;
use Wsklad\Traits\Singleton;

/**
 * Class Form
 *
 * @package Wsklad\Admin\Accounts
 */
abstract class Form extends FormAbstract
{
	use Singleton;

	/**
	 * Lazy load
	 *
	 * @throws Exception
	 */
	protected function init()
	{
		$this->load_fields();

		$this->save();

		add_action(WSKLAD_ADMIN_PREFIX . 'accounts_form_create_show', [$this, 'output_form']);
	}

	/**
	 * Save
	 *
	 * @return bool
	 */
	public function save()
	{
		$post_data = $this->get_posted_data();

		if(!isset($post_data['_wsklad-admin-nonce-accounts-create']))
		{
			return false;
		}

		if(empty($post_data) || !wp_verify_nonce($post_data['_wsklad-admin-nonce-accounts-create'], 'wsklad-admin-accounts-create-save'))
		{
			wsklad_admin()->notices()->create
			(
				[
					'type' => 'error',
					'title' => __('Connection error. Please retry.', 'wsklad')
				]
			);

			return false;
		}

		foreach($this->get_fields() as $key => $field)
		{
			$field_type = $this->get_field_type($field);

			if('title' === $field_type || 'raw' === $field_type)
			{
				continue;
			}

			try
			{
				$this->saved_data[$key] = $this->get_field_value($key, $field, $post_data);
			}
			catch(\Exception $e)
			{
				wsklad_admin()->notices()->create
				(
					[
						'type' => 'error',
						'title' => $e->getMessage()
					]
				);
			}
		}

		$data = $this->get_saved_data();

		if(empty($data['login']) && empty($data['token']))
		{
			wsklad_admin()->notices()->create
			(
				[
					'type' => 'error',
					'title' => __('Account connection error. Login is required.', 'wsklad')
				]
			);

			return false;
		}

		if(empty($data['password']) && empty($data['token']))
		{
			wsklad_admin()->notices()->create
			(
				[
					'type' => 'error',
					'title' => __('Account connection error. Password is required.', 'wsklad')
				]
			);

			return false;
		}

		if(empty($data['token']) && empty($data['login']))
		{
			wsklad_admin()->notices()->create
			(
				[
					'type' => 'error',
					'title' => __('Account connection error. Token is required.', 'wsklad')
				]
			);

			return false;
		}

		$account = new Account();

		$data_storage = $account->get_storage();

		if(!empty($data['login']))
		{
			if($data_storage->is_existing_by_name($data['login']))
			{
				wsklad_admin()->notices()->create
				(
					[
						'type' => 'error',
						'title' => __('Account connection error. Login is exists.', 'wsklad')
					]
				);

				return false;
			}

			/**
			 * Test connection
			 */
			// todo: check connection, get account data

			$account->set_status('draft');
			$account->set_name($data['login']);
		}


		if($account->save())
		{
			wsklad_admin()->notices()->create
			(
				[
					'type' => 'update',
					'title' => __('Account connection success. Account connection id: ' . $account->get_id(), 'wsklad')
					           . ' (<a href="' . wsklad_admin_accounts_get_url('update', $account->get_id()) . '">' . __('edit account', 'wsklad') . '</a>)'
				]
			);

			$this->set_saved_data([]);
			return true;
		}

		wsklad_admin()->notices()->create
		(
			[
				'type' => 'error',
				'title' => __('Account connection error. Please retry saving or change fields.', 'wsklad')
			]
		);

		return false;
	}

	/**
	 * Form show
	 */
	public function output_form()
	{
		$args =
		[
			'object' => $this
		];

		wsklad_get_template('accounts/create_form.php', $args);
	}
}