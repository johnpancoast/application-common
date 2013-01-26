<?php defined('SYSPATH') OR die('No direct script access.');

class HTTP_Exception_403 extends Kohana_HTTP_Exception_403 {

	/**
	 * @var   integer    HTTP 403 Forbidden
	 */
	protected $_code = 403;

	public function get_response()
	{
		Notice::add(Notice::ERROR, 'You do not have access to this page.');

		$renderer = Kostache_Layout::factory();
		$renderer->set_layout('layouts/main');

		// create a view. manually set user notice. return response.
		$view = new View_Empty;
		$response = Response::factory()
		    ->status(403)
		    ->body($renderer->render($view));

		return $response;
	}
}