<?php defined('SYSPATH') or die('No direct script access.');

/**
 * All WWW views should extend this
 */
class View_WWW_Base {
	public function auth_user()
	{
		$auth_user = array();

		// pass the main layout a username if logged in
		if ($user = Auth::instance()->get_user())
		{
			$auth_user['username'] = $user->username;
		}
		return $auth_user;

	}

	public function notices()
	{
		$notices = array();

		// pass in notices (after organizing them)
		// FIXME - we can probably make the notice class just organize how we want
		$n = Notice::render();
		foreach ($n AS $k => $v)
		{
			foreach ($v AS $k2 => $v2)
			{
				// easier for mustache template to find this type
				$v2[$v2['type']] = TRUE;
				$notices[] = $v2;
			}
		}

		return $notices;
	}
}
