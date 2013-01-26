<?php defined('SYSPATH') or die('No direct script access.');

/**
 * valid user
 * @see modules/vendo-acl
 */
class Policy_User extends Policy {
	public function execute(Model_ACL_User $user, array $array = NULL)
	{
		return $user->has_any_role(array('root', 'admin', 'user'));
	}
}
