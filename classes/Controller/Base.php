<?php defined('SYSPATH') or die('No direct script access.');

/**
 * base controller class that all controllers _must_ extend
 */
class Controller_Base extends Controller {
	/**
	 * @var array A map of controller action methods to vendo-acl policies.
	 * @access protected
	 * 
	 * The array's keys are controller action_* methods which are mapped to a
	 * vendo-acl policy or array of policies. These policies will be executed
	 * before the mapped controller method is executed.  You can apply a policy to
	 * all action methods by using the "*" catch-all as an array key. A
	 * controller method that's about to be executed from a request _must_ have a
	 * matching key in the acl_policies or a catch-all must be set. If this isn't
	 * satisfied, the controller method will not be called. If a particular
	 * method does not need to check any acl policies, leave its value as FALSE,
	 * These policies are only called on action_* controller methods before they're
	 * called from a request. If you have other areas where you want acl policies
	 * checked, you must manually add those calls yourself. A failed policy check
	 * will result in an error.
	 *
	 * @see self::before()
	 * @see modules/vendo-acl
	 * @link https://github.com/vendo/acl
	 * @link https://github.com/vendo/vendo/wiki/Acl
	 */
	protected $acl_policies = array();

	/**
	 * @var View_Main The KOstache view object
	 * @access private
	 */
	private $view = NULL;

	/**
	 * @var string The mustache layout file (relative to template/layouts/)
	 * @access private
	 */
	private $layout = 'main';

	/**
	 * set view class
	 * @access public
	 * @param string $view The view class (relative to application/classes/view/)
	 * @param string $layout The layout file (relative to templates/layouts/)
	 */
	public function set_view($view, $layout = NULL)
	{
		if ($layout)
		{
			$this->set_layout($layout);
		}
		$class = 'View_'.$view;
		return $this->set_view_object(new $class);
	}

	/**
	 * set view object
	 * @access public
	 * @param StdClass $view The view object
	 */
	public function set_view_object($view)
	{
		$this->view = $view;
		return $this->view;
	}

	/**
	 * set layout
	 * @param string $layout The layout file (relative to templates/layouts/)
	 * @access public
	 */
	public function set_layout($layout)
	{
		$this->layout = $layout;
	}

	/**
	 * run before anything else
	 */
	public function before()
	{
		parent::before();

		try
		{
			$this->check_acl();
		}
		catch (Policy_Exception $e)
		{
			throw HTTP_Exception::factory(403, 'You do not have access to this page.');
		}
	}

	/**
	 * check vendo-acl policies
	 * @access protected
	 * @throws Policy_Exception if not good
	 */
	protected function check_acl()
	{
		$check = 'action_'.Request::current()->action();

		// even if the value is empty a matched key or catch-all must be set.
		if ( ! isset($this->acl_policies[$check]) && ! isset($this->acl_policies['*']))
		{
			throw new Exception('Must set acl policy for method.');
		}

		// grab policies
		$policies = array();
		if ( ! empty($this->acl_policies[$check]))
		{
			$val = $this->acl_policies[$check];
			$policies = is_array($val) ? $val : array($val);
		}
		if ( ! empty($this->acl_policies['*']))
		{
			$val = $this->acl_policies['*'];
			$val = is_array($val) ? $val : array($val);
			$policies = array_merge($policies, $val);
		}

		// if no matched policies, nothing to check and we can move on.
		if (empty($policies))
		{
			return;
		}

		// grab logged in user. if no user, then they cannot possibly match required policies.
		$user = Auth::instance()->get_user();
		if ( ! $user)
		{
			throw HTTP_Exception::factory(401, 'User not logged in');
		}

		// check policies
		foreach ($policies AS $policy)
		{
			$user->assert($policy);
		}
	}

	/**
	 * The after() method is called after controller action.
	 */
	public function after()
	{
		// set our content if we have a view
		if ($this->view)
		{
			$renderer = Kostache_Layout::factory();
			$renderer->set_layout('layouts/'.$this->layout);
			$this->response->body($renderer->render($this->view));
		}

		parent::after();
	}

}
