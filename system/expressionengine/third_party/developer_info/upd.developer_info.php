<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Install / Uninstall and updates the modules
 *
 * @package			Developer Info
 * @author			Marc Miller <http://www.bigoceanstudios.com>
 * @copyright 	Copyright (c) 2011 Big Ocean Studios <http://www.bigoceanstudios.com>
 * @link				http://github.com/marc524/developer_info.ee_addon/
 * @see					https://github.com/markhuot/geode.ee_addon
 */

require_once PATH_THIRD.'developer_info/config'.EXT;
require_once PATH_THIRD.'developer_info/bosbase'.EXT;

class Developer_info_upd extends BOSBase_upd
{

	var $module_name = DEVINFO_CLASS_NAME;
	var $version = DEVINFO_VERSION;

	public function install()
	{
		$result = parent::install();

		return $result;
	}

} // END CLASS

/* End of file upd.developer_info.php */
/* Location: ./system/expressionengine/third_party/developer_info/upd.developer_info.php */