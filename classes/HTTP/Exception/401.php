<?php defined('SYSPATH') OR die('No direct script access.');

class HTTP_Exception_401 extends Kohana_HTTP_Exception_401 {
	/**
	 * @var   integer    HTTP 401 Forbidden
	 */
	protected $_code = 401;

	public function get_response()
	{
		Notice::add(Notice::ERROR, 'You must login to access this page.');

		$renderer = Kostache_Layout::factory();
		$renderer->set_layout('layouts/main');

		// create a view. manually set user notice. return response.
		$view = new View_WWW_Errors_401;
		$response = Response::factory()
		    ->status(401)
		    ->body($renderer->render($view));

		return $response;
	}
}