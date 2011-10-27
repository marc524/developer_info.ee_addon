<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Base File so we don't have to repeat ourself. I'm lazy.
 *
 * @author			Marc Miller <http://www.bigoceanstudios.com>
 * @copyright 	Copyright (c) 2011 Big Ocean Studios <http://www.bigoceanstudios.com>
 * @see					https://github.com/markhuot/geode.ee_addon
 */

class BOSBase
{
	public function __construct()
	{
		$this->EE =& get_instance();
	}
}

class BOSBase_upd extends BOSBase
{

  // ----------------------------------------------------------------
  /**
   * Installation Method
   *
   * Required by ExpressionEngine
   *
   * @return boolean  TRUE
   */
	public function install()
	{
		$data = array(
			'module_name' => $this->module_name,
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		);

		$this->EE->db->insert('modules', $data);

		return TRUE;
	}

  // ----------------------------------------------------------------
  /**
   * Module Updater
   *
   * Required by ExpressionEngine
   *
   * @return  boolean     TRUE
   */
	public function update($current = '')
	{
 		// Nothing to do here.
		return TRUE;
	}

  // ----------------------------------------------------------------
  /**
   * Module Uninstaller
   *
   * Required by ExpressionEngine
   *
   * @return  boolean     TRUE
   */
	public function uninstall()
	{
		// remove module
		$this->EE->db->where('module_name', $this->module_name);
		$this->EE->db->delete('modules');

		// remove actions
		$this->EE->db->where('class', $this->module_name);
		$this->EE->db->delete('actions');

		return TRUE;
	}

}// END CLASS

/* End of file bosbase.php */
/* Location: ./system/expressionengine/third_party/xxx/bosbase.php */