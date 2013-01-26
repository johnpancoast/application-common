<?php defined('SYSPATH') OR die('No direct access allowed.');

class ORM extends Kohana_ORM {
	protected $_created_column = array('column' => 'create_date', 'format' => 'c');
	protected $_updated_column = array('column' => 'update_date', 'format' => 'c');
}
