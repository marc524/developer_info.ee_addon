<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD. 'developer_info/bosbase' .EXT;

class Developer_info_mcp extends BOSBase
{
	var $_base_url;
	var $_form_base;
	var $_site_id;
	var $EE;

	var $override_check = '';

	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->EE =& get_instance();

		// Load Helpers
		$this->EE->load->library('developer_info_helper');

		// Set base URL to the module so there's less typing elsewhere in this class.
    $this->_base_url = BASE . AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=developer_info';

		$this->_site_id = $this->EE->config->item('site_id');
		$this->mcp_globals();

		// Set the right nav
		$this->EE->cp->set_right_nav(array(
			'developer_info_channels' 	=> $this->_base_url,
			'developer_info_files'  		=> $this->_base_url . AMP . 'method=files',
			'developer_info_templates'  => $this->_base_url . AMP . 'method=templates'
		 ));
	}



	// ********************************************************************************* //
	/* Channel Fields Page - Default Index Page */
	function index() {
		$vars = array();

    $this->EE->load->library('javascript');
		$this->EE->load->library('table');
		$this->EE->developer_info_helper->_set_page_title(lang('developer_info_module_name'), lang('developer_info_channels'));

		$_base_admin_content = BASE . AMP . 'C=admin_content';
		$channels_new_link = $_base_admin_content . AMP . 'M=channel_add';
		$channels_new_fg_link = $_base_admin_content . AMP . 'M=field_group_edit';
		$channels_new_stats_link = $_base_admin_content . AMP . 'M=status_group_edit';
		$channels_new_cats_link = $_base_admin_content . AMP . 'M=edit_category_group';
		$channels_all_fg_link = $_base_admin_content . AMP . 'M=field_group_management';

		$vars['channels_new_btn'] = '<a href="' . $channels_new_link . '">' . lang('channel_new') . '</a>';
		$vars['channels_new_fg_btn'] = '<a href="' . $channels_new_fg_link . '">' . lang('channel_new_field_group') . '</a>';
		$vars['channels_new_stats_btn'] = '<a href="' . $channels_new_stats_link . '">' . lang('channel_new_status_group') . '</a>';
		$vars['channels_new_cats_btn'] = '<a href="' . $channels_new_cats_link . '">' . lang('channel_new_category_group') . '</a>';
		$vars['channels_all_fg_btn'] = '<a href="' . $channels_all_fg_link . '">' . lang('channels_all_fg_link') . '</a>';
		$vars['wygwam_configs_btn'] = $this->EE->developer_info_helper->_set_wygwam_configs_link();
		$vars['dir_col_head'] = lang('channel_col_head_info');
		$vars['site_id'] = '<p id="di_site-id">' . lang('siteid') . $this->_site_id . '</p>';

		// -------------------------------------------
		//  Channels Query
		// -------------------------------------------
		$channel_query = $this->EE->db->select('channel_id, channel_name, channel_title, field_group, status_group, cat_group')
                									->from('exp_channels')
                									->where('site_id', $this->_site_id)
                									->order_by('channel_title', 'asc')
                									->get();

		if ($channel_query->num_rows() > 0)
		{
			$channels_select = array();
			$vars['channels'] = array();
			foreach($channel_query->result_array() as $row)
			{
				$channels_select[$row['channel_id']] = $row['channel_title'];
				$vars['channels'][$row['channel_id']]['channel_id'] = $row['channel_id'];
				$vars['channels'][$row['channel_id']]['channel_name'] = $row['channel_name'];
				$vars['channels'][$row['channel_id']]['channel_title'] = $row['channel_title'];
				$vars['channels'][$row['channel_id']]['field_group_id'] = $row['field_group'];

				$vars['channels'][$row['channel_id']]['categories'] = $this->EE->developer_info_helper->_display_channel_category_groups($row['cat_group']);
				$vars['channels'][$row['channel_id']]['statuses'] = $this->EE->developer_info_helper->_display_channel_statuses($row['status_group']);
				$vars['channels'][$row['channel_id']]['field_group_name'] = $this->EE->developer_info_helper->_display_field_group_name($row['field_group']);

				$vars['channels'][$row['channel_id']]['channel_fields'] = $this->EE->developer_info_helper->_display_fields($row['field_group']);
				$vars['channels'][$row['channel_id']]['channel_query'] = $this->EE->developer_info_helper->_generate_query($row['field_group'], $row['channel_id']);

				$channel_edit_entries_link = BASE . AMP . 'C=content_edit' . AMP . 'channel_id=' . $row['channel_id'];
				$chnnel_edit_preferences_link = $_base_admin_content . AMP . 'M=channel_edit' . AMP . 'channel_id=' . $row['channel_id'];
				$channel_edit_groups_link = $_base_admin_content . AMP . 'M=channel_edit_group_assignments' . AMP . 'channel_id=' . $row['channel_id'];
				$channel_edit_fg_link = $_base_admin_content . AMP . 'M=field_management' . AMP . 'group_id=' . $row['field_group'];
				$channel_new_field_link = $_base_admin_content . AMP . 'M=field_edit' . AMP . 'group_id=' . $row['field_group'];

				$vars['channels'][$row['channel_id']]['channel_edit_entries'] = '<a href="' . $channel_edit_entries_link . '">' . lang('edit_entries') . '</a>';
				$vars['channels'][$row['channel_id']]['chnnel_edit_preferences'] = '<a href="' . $chnnel_edit_preferences_link . '">' . lang('edit_prefs') . '</a>';
				$vars['channels'][$row['channel_id']]['channel_edit_groups'] = '<a href="' . $channel_edit_groups_link . '">' . lang('edit_groups') . '</a>';
				$vars['channels'][$row['channel_id']]['channel_edit_fg'] = '<a href="' . $channel_edit_fg_link . '">' . lang('edit_fg') . '<strong>"' . $this->EE->developer_info_helper->_display_field_group_name_quick($row['field_group']) . '"</strong></a>';
				$vars['channels'][$row['channel_id']]['channel_new_field'] = '<a href="' . $channel_new_field_link . '">' . lang('channel_new_field') . '<strong>"' . $this->EE->developer_info_helper->_display_field_group_name_quick($row['field_group']) . '"</strong></a>';
			}

			//Create the dropdown scroller navigation
			$vars['all_channels'] = form_dropdown('channel_scroll', $channels_select, '', 'id="channel_scroll_id"');

			//Load the page view
			return $this->EE->load->view('channels', $vars, TRUE);
		}
		else
		{
			//Hmm, we haven't created any channels yet. That'll break the pretty view file, so let's load a different view.
			$vars['all_channels'] = lang('no_channels');
			return $this->EE->load->view('no-channels', $vars, TRUE);
		}
	}

	// ********************************************************************************* //
	/* File Upload Locations Page */
	function files() {
		$vars = array();

	  $this->EE->load->library('javascript');
		$this->EE->jquery->tablesorter(' .mainTable', '{
			widgets: ["zebra"]
		}');
		$this->EE->javascript->compile();

		$this->EE->developer_info_helper->_set_page_title(lang('developer_info_module_name'), lang('developer_info_files'));

		$_base_admin_files = BASE . AMP . 'C=content_files';
		$files_new_link = $_base_admin_files . AMP . 'M=edit_upload_preferences';
		$files_prefs_link = $_base_admin_files . AMP . 'M=file_upload_preferences';

		$vars['files_new_btn'] = '<a href="' . $files_new_link . '">' . lang('files_new') . '</a>';
		$vars['files_prefs_btn'] = '<a href="' . $files_prefs_link . '">' . lang('files_prefs') . '</a>';
		$vars['files_mgr_btn'] = '<a href="' . $_base_admin_files . '">' . lang('files_manager') . '</a>';

		// -------------------------------------------
		//  Files Query
		// -------------------------------------------
		$files_query = $this->EE->db->select('id, name, server_path, url, allowed_types, max_size, max_height, max_width')
              									->from('exp_upload_prefs')
              									->where('site_id', $this->_site_id)
              									->order_by('id', 'asc')
              									->get();

		if ($files_query->num_rows())
		{
			foreach($files_query->result_array() as $row)
			{
				$edit_url = BASE . AMP . 'C=content_files' . AMP . 'M=edit_upload_preferences' . AMP . 'id=' . $row['id'];
				$vars['files'][$row['id']]['id'] = $row['id'];
				$vars['files'][$row['id']]['name'] = '<a href="' . $edit_url. '">' . $row['name'] . '</a>';
				$vars['files'][$row['id']]['server_path'] = $row['server_path'];
				$vars['files'][$row['id']]['url'] = $row['url'];
				$vars['files'][$row['id']]['allowed_types'] = $row['allowed_types'];
				$vars['files'][$row['id']]['max_size'] = $row['max_size'] == '' ? '' : round((intval($row['max_size']) / 1024), 2);
				$vars['files'][$row['id']]['max_height'] = $row['max_height'];
				$vars['files'][$row['id']]['max_width'] = $row['max_width'];
			}

			//Load the page view
			return $this->EE->load->view('files', $vars, TRUE);
		}
		else
		{
			//Hmm, we haven't created any file upload paths yet. That'll break the pretty view file, so let's load a different view.
			$vars['all_files'] = lang('no_file_uploads');
			return $this->EE->load->view('no-files', $vars, TRUE);
		}
	}

	// ********************************************************************************* //
	/* Templates Page */
	function templates() {
		$vars = array();

	  $this->EE->load->library('javascript');
		$this->EE->jquery->tablesorter(' .mainTable', '{
			widgets: ["zebra"]
		}');
		$this->EE->javascript->compile();

		$this->EE->developer_info_helper->_set_page_title(lang('developer_info_module_name'), lang('developer_info_templates'));

		$_base_admin_design = BASE . AMP . 'C=design';
		$templates_mgr_link = $_base_admin_design . AMP . 'M=manager';
		$templates_prefs_link = $_base_admin_design . AMP . 'M=template_preferences_manager';
		$templates_new_grp_link = $_base_admin_design . AMP . 'M=new_template_group';
		$templates_snippets_link = $_base_admin_design . AMP . 'M=snippets';
		$templates_glob_vars_link = $_base_admin_design . AMP . 'M=global_variables';
		$templates_new_temp_link = $_base_admin_design . AMP . 'M=new_template' . AMP . ' group_id=';

		$vars['templates_mgr_btn'] = '<a href="' . $templates_mgr_link . '">' . lang('templates_mgr') . '</a>';
		$vars['templates_prefs_btn'] = '<a href="' . $templates_prefs_link . '">' . lang('templates_prefs') . '</a>';
		$vars['templates_new_btn'] = '<a href="' . $templates_new_grp_link . '">' . lang('templates_new_group') . '</a>';
		$vars['templates_snippets_btn'] = '<a href="' . $templates_snippets_link . '">' . lang('templates_snippets') . '</a>';
		$vars['templates_glob_vars_btn'] = '<a href="' . $templates_glob_vars_link . '">' . lang('templates_global_variables') . '</a>';

		// -------------------------------------------
		//  Templates Query
		// -------------------------------------------
		$templates_query = $this->EE->db->select('group_id, group_name, is_site_default')
	                									->from('exp_template_groups')
	                									->where('site_id', $this->_site_id)
	                									->get();

		if ($templates_query->num_rows())
		{
			$templates_select = array();
			foreach($templates_query->result_array() as $row)
			{
				$templates_select[$row['group_id']] = $row['group_name'];
				$vars['templates'][$row['group_id']]['group_id'] = $row['group_id'];
				$vars['templates'][$row['group_id']]['group_name'] = $row['group_name'];
				$vars['templates'][$row['group_id']]['is_site_default'] = $row['is_site_default'];

				$vars['templates'][$row['group_id']]['template_info'] = $this->EE->developer_info_helper->_display_templates($row['group_id']);
				$vars['templates'][$row['group_id']]['new_template'] = '<a href="' . $templates_new_temp_link . $row['group_id'] . '">' . lang('templates_new_template') . '<strong>' . $row['group_name'] . '</strong></a>';
			}

			//Create the dropdown scroller navigation
			$vars['all_templates'] = form_dropdown('channel_scroll', $templates_select, '', 'id="channel_scroll_id"');

			//Load the page view
			return $this->EE->load->view('templates', $vars, TRUE);
		}
		else
		{
			//Hmm, we haven't created any templates yet. That'll break the pretty view file, so let's load a different view.
			$vars['all_templates'] = lang('no_templates');
			return $this->EE->load->view('no-templates', $vars, TRUE);
		}
	}


	// ********************************************************************************* //
	/* Set up Global files */
	public function mcp_globals()
	{
		$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="' . $this->EE->config->item('theme_folder_url') . 'third_party/developer_info/css/developer_info.css" />');

		$override_check = $this->EE->config->item('theme_folder_path') . 'cp_themes/default/css/override.css';
		if (file_exists($override_check)) {
			$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="' . $this->EE->config->item('theme_folder_url') . 'third_party/developer_info/css/di-with_override.css" />');
		}

		$this->EE->cp->add_to_foot('<script type="text/javascript" src="' . $this->EE->config->item('theme_folder_url') . 'third_party/developer_info/js/jquery.cookie.js"></script>');
		$this->EE->cp->add_to_foot('<script type="text/javascript" src="' . $this->EE->config->item('theme_folder_url') . 'third_party/developer_info/js/developer_info.js"></script>');
	}

  // ********************************************************************************* //

} // END CLASS

/* End of file mcp.developer_info.php */
/* Location: ./system/expressionengine/third_party/developer_info/mcp.developer_info.php */