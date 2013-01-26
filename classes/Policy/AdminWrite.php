<?php defined('SYSPATH') or die('No direct script access.');

/**
 * policy for editing an admin page
 * @see modules/vendo-acl
 */
class Policy_AdminWrite extends Policy {
	public function execute(Model_ACL_User $user, array $array = NULL)
	{
		return $user->has_any_role(array('root', 'admin'));
	}
}
