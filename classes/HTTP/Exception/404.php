<?php defined('SYSPATH') OR die('No direct script access.');

class HTTP_Exception_404 extends Kohana_HTTP_Exception_404 {

	/**
	 * @var   integer    HTTP 404 Forbidden
	 */
	protected $_code = 404;

	public function get_response()
	{
		Notice::add(Notice::ERROR, 'Page not found.');

		$renderer = Kostache_Layout::factory();
		$renderer->set_layout('layouts/main');

		// create a view. manually set user notice. return response.
		$view = new View_Empty;
		$response = Response::factory()
		    ->status(404)
		    ->body($renderer->render($view));

		return $response;
	}
}