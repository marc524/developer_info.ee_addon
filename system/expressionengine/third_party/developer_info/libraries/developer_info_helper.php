<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Developer Info Helper File
 *
 * @package			Developer Info
 * @author			Marc Miller <http://www.bigoceanstudios.com>
 * @copyright 		Copyright (c) 2013 Big Ocean Studios <http://www.bigoceanstudios.com>
 * @link			http://github.com/marc524/developer_info.ee_addon/
 */

class Developer_Info_helper
{

	var $_site_id;

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct()
	{
		// Creat EE Instance
		$this->EE =& get_instance();
		$this->_site_id = $this->EE->config->item('site_id');

		// Set base URL to the module so there's less typing elsewhere in this class.
   		$this->_base_url = BASE. AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=developer_info';
    	$this->_base_url_admin = BASE. AMP . 'C=admin_content';
    	$this->_base_url_modules = BASE. AMP . 'C=addons_modules' . AMP . 'M=show_module_cp';
	}

	// ********************************************************************************* //
	/* Set Page Title */
	function _set_page_title($line = 'developer_info_module_name', $page='')
	{
		if ($line != 'developer_info_module_name')
		{
			$this->EE->cp->set_breadcrumb($this->_base_url, $this->EE->lang->line('developer_info_module_name'));
		}
		if ($page != '')
		{
			$line = $line . ': ' . $page;
		}
		if (version_compare(APP_VER, '2.6', '>='))
		{
			$this->EE->view->cp_page_title = $this->EE->lang->line($line);
		} 
		else 
		{
			$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line($line));
		}
	}

	// ********************************************************************************* //
	/* Check to see if EE's Fieldtype Safecracker File is installed */
	function _check_ee_safecracker_file()
	{
		$wygwam_check = $this->EE->db->select('name')
							->from('exp_fieldtypes')
							->where('name', 'safecracker_file')
							->limit(1)
							->get();

		return ($wygwam_check->num_rows == 1);
	}

	// ********************************************************************************* //
	/* Check to see if Pixel & Tonic's Fieldtype Wygwam is installed */
	function _check_pt_wygwam()
	{
		$wygwam_check = $this->EE->db->select('name')
							->from('exp_fieldtypes')
							->where('name', 'wygwam')
							->limit(1)
							->get();

		return ($wygwam_check->num_rows == 1);
	}

	// ********************************************************************************* //
	/* Check to see if Pixel & Tonic's Fieldtype Matrix is installed */
	function _check_pt_matrix()
	{
		$matrix_check = $this->EE->db->select('name')
							->from('exp_fieldtypes')
							->where('name', 'matrix')
							->limit(1)
							->get();

		return ($matrix_check->num_rows == 1);
	}

	// ********************************************************************************* //
	/* Check to see if Pixel & Tonic's Fieldtype Assets is installed */
	function _check_pt_assets()
	{
		$assets_check = $this->EE->db->select('name, version')
						->from('exp_fieldtypes')
						->where('name', 'assets')
						->limit(1)
						->get();

		return ($assets_check->num_rows == 1);
	}

	// ********************************************************************************* //
	/* Check to see if DevDemon's Fieldtype Channel Images is installed */
	function _check_ci()
	{
		$ci_check = $this->EE->db->select('name')
						->from('exp_fieldtypes')
						->where('name', 'channel_images')
						->limit(1)
						->get();

		return ($ci_check->num_rows == 1);
	}

	// ********************************************************************************* //
	/* Check to see if DevDemon's Fieldtype Channel Files is installed */
	function _check_cf()
	{
		$ci_check = $this->EE->db->select('name')
						->from('exp_fieldtypes')
						->where('name', 'channel_files')
						->limit(1)
						->get();

		return ($ci_check->num_rows == 1);
	}

	// ********************************************************************************* //
	/* Show the category groups */
	function _display_channel_category_groups ($group_ids)
	{
		$c = '';
		if ( !$group_ids)
		{
			$c = lang('no_cats');
		}
		else
		{
			$cat_groups = explode('|', $group_ids);
			if (count($cat_groups) > 0)
			{
				// Loop through the category groups.
				foreach ($cat_groups AS $group_id)
				{
					$edit_url = $this->_base_url_admin . AMP . 'M=category_editor' . AMP . 'group_id=' . $group_id;

					// Retrieve the category group name
					$group_name = $this->EE->db->select('group_name')
									->from('exp_category_groups')
									->where('group_id', $group_id)
									->where('site_id', $this->_site_id)
									->limit(1)
									->get();

					$c .= '<div class="di_list-head"><a href="' . $edit_url . '">(ID: ' . $group_id . ') ' . $group_name->row('group_name') . '</a></div>';
					$c .= '<p>';
					$cats = $this->_display_channel_categories($group_id, 0, '');
					$c .= ($cats == '') ? lang('no_cats') : $cats;
					$c .= '</p>';
				}
			}
		}
		return $c;
	}

	// ********************************************************************************* //
	/* Show the categories */
	function _display_channel_categories($group_id, $parent_id, $prefix)
	{
		$c = '';

		$img_root = '<img border="0" alt="" title="" height="14" src="' . $this->EE->config->item('theme_folder_url') . 'cp_global_images/';
		$nested_arrow = $img_root . 'cat_marker.gif" width="18" />';
		$spacer	= $img_root . 'clear.gif" width="24" />';
		$thin_spacer = $img_root . 'clear.gif" width="1" />';

		// Retrieve the categories.
		$cats = $this->EE->db->select('cat_id, cat_name, parent_id')
					->from('exp_categories')
					->where('group_id', $group_id)
					->where('parent_id', $parent_id)
					->where('site_id', $this->_site_id)
					->order_by('cat_order', 'asc')
					->get();

			if ($cats->num_rows > 0)
			{
				foreach($cats->result_array() as $cat)
				{
					$c .= ($prefix != '') ? $prefix . $nested_arrow : $prefix;
					$c .= '(ID: ' . $cat['cat_id'] . ') ' . $cat['cat_name'] . $thin_spacer . '<br />';
					$c .= $this->_display_channel_categories($group_id, $cat['cat_id'], $prefix . $spacer);
				}
			}

		return $c;
	}

	// ********************************************************************************* //
	/* Show the statuses */
	function _display_channel_statuses($group_id)
	{
		$c = '';
		if ( !$group_id)
		{
			$c = lang('no_stats');
		}
		else
		{
			$edit_url = $this->_base_url_admin . AMP . 'M=status_management' . AMP . 'group_id=' . $group_id;

			// Retrieve the statuse group name.
			$group_name = $this->EE->db->select('group_name')
							->from('exp_status_groups')
							->where('group_id', $group_id)
							->where('site_id', $this->_site_id)
							->get();

			if ($group_name->num_rows == 1)
			{
				// Output the status group name.
				$c .= '<div class="di_list-head"><a href="' . $edit_url . '">(ID: ' . $group_id . ') ' . $group_name->row('group_name') . '</a></div>';
				$c .= '<p>';

				// Retrieve the statuses from this status group.
				$statuses = $this->EE->db->select('status_id, status')
								->from('exp_statuses')
								->where('group_id', $group_id)
								->where('site_id', $this->_site_id)
								->order_by('status_order', 'asc')
								->get();

				if ($statuses->num_rows == 0)
				{
					$c .= lang('no_stats');
				}
				else
				{
					foreach($statuses->result_array() as $s)
					{
						$c .= '(ID: ' . $s['status_id'] . ') ' . $s['status'] . '<br />';
					}
				}
				// Close the status "list" paragraph.
				$c .= '</p>';
			}
		}
		return $c;
	}

	// ********************************************************************************* //
	/* Show the field group name */
	function _display_field_group_name($group_id)
	{
		$edit_url = $this->_base_url_admin . AMP . 'M=field_management' . AMP . 'group_id=' . $group_id;
		$c='';
		if ($group_id)
		{
			$field_group_name = $this->_display_field_group_name_quick($group_id);
			$c .= '<div class="di_list-head"><a href="' . $edit_url . '">(ID: ' . $group_id . ') ' . $field_group_name . '</a></div>';
		}
		else
		{
			$c = lang('no_fg');
		}
		return $c;
	}

	// ********************************************************************************* //
	/* Show the field group name */
	function _display_field_group_name_quick($group_id)
	{
		$c='';
		if ( !$group_id)
		{
			$c = lang('no_fg');
		}
		else
		{
			$edit_url = $this->_base_url_admin . AMP . 'M=field_management' . AMP . 'group_id=' . $group_id;

			$group_name = $this->EE->db->select('group_name')
							->from('exp_field_groups')
							->where('group_id', $group_id)
							->where('site_id', $this->_site_id)
							->get();

			if ($group_name->num_rows == 1)
			{
				// Output the field group name.
				$c .= $group_name->row('group_name');
			}
		}
		return $c;
	}

	// ********************************************************************************* //
	/* Get the file directory and build the edit link */
	function _get_file_dir($dir_id)
	{
		$file_dir = '<span class="di_font-smaller">' . lang('upload_dir');
		
		if ($dir_id == 'all') 
		{
			$file_dir .= lang('all');
		} 
		else 
		{
			$file_dir_query = $this->EE->db->select('name')
								->from('exp_upload_prefs')
								->where('id', $dir_id)
								->get();

			if ($file_dir_query->num_rows() > 0)
			{
				$edit_file_dir =  BASE . AMP . 'C=content_files' . AMP . 'M=edit_upload_preferences' . AMP . 'id=' . $dir_id;
				$file_dir .= '<a href="' . $edit_file_dir . '">' . $file_dir_query->row('name') . '</a>';
			}
		}

		$file_dir .= '</span>';
		return $file_dir;
	}

	// ********************************************************************************* //
	/* Get the file directory allowed types */
	function _get_file_dir_types($dir_type)
	{
		$file_dir = '<br /><span class="di_font-smaller">' . lang('file_type');
		$file_dir .= ucfirst($dir_type);
		$file_dir .= '</span>';
		return $file_dir;
	}

	// ********************************************************************************* //
	/* Get the file directories from array and build the edit links */
	function _get_file_dir_array($dir_id)
	{
		$file_dir = '<span class="di_font-smaller">' . lang('upload_dirs');

		if ($dir_id == 'all') 
		{
			$file_dir .= lang('all');
		} 
		else 
		{
			foreach ($dir_id as $dir) 
			{ 
				$dir = str_replace("ee:", "", $dir);
				$file_dir_query = $this->EE->db->select('name')
									->from('exp_upload_prefs')
									->where('id', $dir)
									->get();

				$edit_file_dir =  BASE . AMP . 'C=content_files' . AMP . 'M=edit_upload_preferences' . AMP . 'id=' . $dir;
				$file_dir .= '<a href="' . $edit_file_dir . '">' . $file_dir_query->row('name') . '</a> | ';
			}
		}
		$file_dir = substr($file_dir, 0, -3);
		$file_dir .= '</span>';
		return $file_dir;
	}
	// ********************************************************************************* //
	/* Get the Wygwam config name and settings */
	function _get_wygwam_config($config_id)
	{
		$query = $this->EE->db->select('config_name, settings')
					->from('exp_wygwam_configs')
					->where('config_id', $config_id)
					->get();
		return $query;
	}

	// ********************************************************************************* //
	/* Create links to the Wygwam configuration editor */
	function _set_wygwam_configs_link()
	{
		$c = '';
		$wygwam_configs_link = $this->_base_url_modules . AMP . 'module=wygwam' . AMP . 'method=configs';
		if ($this->_check_pt_wygwam())
		{
			$c = '<li> | </li><li><a href="' .$wygwam_configs_link. '">' . lang('wygwam_configs_link'). '</a></li>';
		}
		return $c;
	}

	// ********************************************************************************* //
	/* Get the file directory for a Wygwam field and build the edit link */
	function _get_wygwam_file_dir($config_id, $space='')
	{
		$wygwam_file_id = '';
		$c='';
		$wygwam_config = $this->_get_wygwam_config($config_id);
		$wygwam_settings_decoded = unserialize(base64_decode($wygwam_config->row('settings')));

		if (array_key_exists('upload_dir', $wygwam_settings_decoded))
		{
			$wygwam_file_id = $wygwam_settings_decoded['upload_dir'];
		}

		$edit_wygwam_config =  $this->_base_url_modules . AMP . 'module=wygwam' . AMP . 'method=config_edit' . AMP . 'config_id=' .$config_id;
		$c = '<span class="di_font-smaller">' . lang('pt_config'). ':</span> ' . '<a href="' . $edit_wygwam_config . '">' . $wygwam_config->row('config_name') . '</a>';

		if ($wygwam_file_id !='')
		{
			$c .= '<br /><span class="di_font-smaller">' . $space . $this->_get_file_dir($wygwam_file_id). '</span>';
		}
		else
		{
			$c .= '<br /><span class="di_font-smaller">' . $space . lang('no_file_upload') . '</span>';
		}
		return $c;
	}

	// ********************************************************************************* //
	/* Show each channel field information */
	function _display_fields($group_id)
	{
		$c='';
		if ( !$group_id)
		{
			$c .= '<tr  class="di_row1"><td colspan="10">';
			$c .= lang('no_fg');
			$c .= "</td></tr>";
		}
		else
		{
			$channel_select = 'field_id, group_id, field_order, field_name, field_label, field_type, field_fmt, field_required, field_search, field_settings, field_list_items, field_pre_channel_id, field_pre_field_id, field_is_hidden';

			if (version_compare(APP_VER, '2.6', '>='))
				$channel_select .= ', field_related_id';

			$channel_fields = $this->EE->db->select($channel_select)
								->from('exp_channel_fields')
								->where('group_id', $group_id)
								->where('site_id', $this->_site_id)
								->order_by('field_order', 'asc')
								->get();

			if ($channel_fields->num_rows > 0)
			{
				$i = 0;
				foreach($channel_fields->result_array() as $row)
				{
					$edit_field_url = $this->_base_url_admin . AMP . 'M=field_edit' . AMP . 'field_id=' . $row['field_id'] . AMP . 'group_id=' . $row['group_id'];
					$ft_info = '';
					$wygwam_file_id = '';
					$field_items_decoded = unserialize(base64_decode($row['field_settings']));

					//// CHECK FILE TYPES ////

					// Get File directory information
					if ($row['field_type'] == 'file')
					{
						if (array_key_exists('allowed_directories', $field_items_decoded))
						{
							$ft_info = $this->_get_file_dir($field_items_decoded['allowed_directories']);

							//hmm, 2.7 seems to have changed this:
							if (array_key_exists('field_content_type', $field_items_decoded))
								$ft_info .= $this->_get_file_dir_types($field_items_decoded['field_content_type']);
							
							if (array_key_exists('content_type', $field_items_decoded))
								$ft_info .= $this->_get_file_dir_types($field_items_decoded['content_type']);
							
						}
						else
						{
							$ft_info = lang('no_file_set');
						}
					}

					// Get Safecracker File directory information
					if ($row['field_type'] == 'safecracker_file')
					{
						if (array_key_exists('safecracker_upload_dir', $field_items_decoded))
						{
							$ft_info = $this->_get_file_dir($field_items_decoded['safecracker_upload_dir']);
							$ft_info .= $this->_get_file_dir_types($field_items_decoded['file_field_content_type']);
						}
						else
						{
							$ft_info = lang('no_file_upload');
						}
					}

					// Get Default EE Select, Checkboxes, Radio buttons, Multi-select information
					if ($row['field_type'] == 'select' || $row['field_type'] == 'checkboxes' || $row['field_type'] == 'radio' || $row['field_type'] == 'multi_select')
					{
						if ($row['field_pre_channel_id'] > 0)
						{
							$field_id = $row['field_pre_field_id'];

							$select_query = $this->EE->db->select('f.field_label, c.channel_title')
												->from('exp_channel_fields f')
												->join('exp_channels c', 'f.group_id = c.channel_id')
												->where('f.field_id', $field_id)
												->get();

							$ft_info = '<span class="di_font-smaller">' . lang('pre-pop'). '</span>';
							$ft_info .= $select_query->row('channel_title') . ': ' . $select_query->row('field_label');
						}
						else
						{
							$ft_info = str_replace('\n', '<br />', $row['field_list_items']);
						}
					}

					// Get Relationship details (EE 2.5.5 and older)
					if ($row['field_type'] == 'rel')
					{
						$rel_id = $row['field_related_id'];
						$rel_query = $this->EE->db->select('channel_title')
										->from('exp_channels')
										->where('channel_id', $rel_id)
										->get();

						$ft_info = '<span class="di_font-smaller">' . lang('related'). '</span>';
						$ft_info .= $rel_query->row('channel_title');
					}

					// Get Relationship details (EE 2.6)
					if ($row['field_type'] == 'relationship')
					{
						$ft_info = '';
						$rel_info_decoded = unserialize(base64_decode($row['field_settings']));
						if (array_key_exists('channels', $rel_info_decoded))
						{
							$rel_channels = $rel_info_decoded['channels'];

							if(count($rel_channels)>0)
								$ft_info .= '<span class="di_font-smaller">' . lang('pt_playa_channel'). '</span><br />';

							foreach($rel_channels as $channel)
							{
								$channel_query = $this->EE->db->select('channel_title')
													->from('exp_channels')
													->where('channel_id', $channel)
													->get();

								$ft_info .= '&nbsp;&nbsp;' . $channel_query->row('channel_title') . '<br />';
							}
						}
						if (array_key_exists('categories', $rel_info_decoded))
						{
							$rel_cats = $rel_info_decoded['categories'];

							if(count($rel_cats)>0)
								$ft_info .= '<span class="di_font-smaller">' . lang('pt_playa_cats'). '</span><br />';
							
							foreach($rel_cats as $cat)
							{
								$cats_query = $this->EE->db->select('cat_name')
												->from('exp_categories')
												->where('cat_id', $cat)
												->get();

								$ft_info .= '&nbsp;&nbsp;' . $cats_query->row('cat_name') . '<br />';
							}
						}
						if (array_key_exists('authors', $rel_info_decoded))
						{
							$rel_authors = $rel_info_decoded['authors'];

							if(count($rel_authors)>0)
								$ft_info .= '<span class="di_font-smaller">' . lang('pt_playa_author'). '</span><br />';
							
							foreach($rel_authors as $author)
							{
								if(strpos($author, 'm_')!== false)
								{
									$author_id = substr($author, 2);
									$author_query = $this->EE->db->select('screen_name')
													->from('exp_members')
													->where('member_id', $author_id)
													->get();

									$ft_info .= '&nbsp;&nbsp;' . $author_query->row('screen_name') . '<br />';			
								}
								if(strpos($author, 'g_')!== false)
								{
									$author_group_id = substr($author, 2);
									$author_query = $this->EE->db->select('group_title')
													->from('exp_member_groups')
													->where('group_id', $author_group_id)
													->get();

									$ft_info .= '&nbsp;&nbsp;' . lang('rel_member_group') . ':&nbsp;' . $author_query->row('group_title') . '<br />';			
								}
							}
						}
						if (array_key_exists('statuses', $rel_info_decoded))
						{
							$rel_statuses = $rel_info_decoded['statuses'];

							if(count($rel_statuses)>0)
								$ft_info .= '<span class="di_font-smaller">' . lang('pt_playa_status'). '</span><br />';
							
							foreach($rel_statuses as $status)
							{
								$ft_info .= '&nbsp;&nbsp;' . $status . '<br />';
							}
						}
					}

					// Get PT Switch and PT Fieldpack Switch details
					if ($row['field_type'] == 'pt_switch' || $row['field_type'] == 'fieldpack_switch')
					{
						$field_items_decoded = unserialize(base64_decode($row['field_settings']));
						$pt_off_label = $field_items_decoded['off_label'];
						$pt_off_val = $field_items_decoded['off_val'];
						$pt_on_label = $field_items_decoded['on_label'];
						$pt_on_val = $field_items_decoded['on_val'];
						$pt_default = $field_items_decoded['default'];

						$ft_info = lang('pt_off_label') . ': ' . $pt_off_label . '<br />';
						$ft_info .= lang('pt_off_value') . ': ' . $pt_off_val . '<br />';
						$ft_info .= lang('pt_on_label') . ': ' . $pt_on_label . '<br />';
						$ft_info .= lang('pt_on_value') . ': ' . $pt_on_val . '<br />';
						$ft_info .= lang('pt_default') . ': ' . $pt_default;
					}				

					// Get the rest of the PT Dive Bar FT and New PT Fieldpack details
					if ($row['field_type'] == 'pt_pill' || 
						$row['field_type'] == 'pt_dropdown' || 
						$row['field_type'] == 'pt_checkboxes' || 
						$row['field_type'] == 'pt_multiselect' || 
						$row['field_type'] == 'pt_radio_buttons' ||
						$row['field_type'] == 'fieldpack_pill' || 
						$row['field_type'] == 'fieldpack_dropdown' || 
						$row['field_type'] == 'fieldpack_checkboxes' || 
						$row['field_type'] == 'fieldpack_multiselect' || 
						$row['field_type'] == 'fieldpack_radio_buttons'
						)
					{
						$ft_info = '';
						$field_items_decoded = unserialize(base64_decode($row['field_settings']));
						if (array_key_exists('options', $field_items_decoded))
						{
							$pt_options = $field_items_decoded['options'];
							foreach ($pt_options as $field_options=>$value)
							{
								$ft_info .= $field_options;
								$ft_info .= ' : ';
								$ft_info .= $value;
								$ft_info .= '<br />';
							}
						}
					}

					// Get Playa details
					if ($row['field_type'] == 'playa')
					{
						$ft_info = '';
						$playa_info_decoded = unserialize(base64_decode($row['field_settings']));
						if (array_key_exists('channels', $playa_info_decoded))
						{
							$ft_info .= '<span class="di_font-smaller">' . lang('pt_playa_channel'). '</span><br />';
							$playa_channels = $playa_info_decoded['channels'];
							foreach($playa_channels as $channel)
							{
								$channel_query = $this->EE->db->select('channel_title')
													->from('exp_channels')
													->where('channel_id', $channel)
													->get();

								$ft_info .= '&nbsp;&nbsp;' . $channel_query->row('channel_title') . '<br />';
							}
						}
						if (array_key_exists('cats', $playa_info_decoded))
						{
							$ft_info .= '<span class="di_font-smaller">' . lang('pt_playa_cats'). '</span><br />';
							$playa_cats = $playa_info_decoded['cats'];
							foreach($playa_cats as $cat)
							{
								$cats_query = $this->EE->db->select('cat_name')
												->from('exp_categories')
												->where('cat_id', $cat)
												->get();

								$ft_info .= '&nbsp;&nbsp;' . $cats_query->row('cat_name') . '<br />';
							}
						}
						if (array_key_exists('authors', $playa_info_decoded))
						{
							$ft_info .= '<span class="di_font-smaller">' . lang('pt_playa_author'). '</span><br />';
							$playa_authors = $playa_info_decoded['authors'];
							foreach($playa_authors as $author)
							{
								$author_query = $this->EE->db->select('screen_name')
													->from('exp_members')
													->where('member_id', $author)
													->get();

								$ft_info .= '&nbsp;&nbsp;' . $author_query->row('screen_name') . '<br />';
							}
						}
						if (array_key_exists('statuses', $playa_info_decoded))
						{
							$ft_info .= '<span class="di_font-smaller">' . lang('pt_playa_status'). '</span><br />';
							$playa_statuses = $playa_info_decoded['statuses'];
							foreach($playa_statuses as $status)
							{
								$ft_info .= '&nbsp;&nbsp;' . $status . '<br />';
							}
						}
					}

					// Get Wygwam details
					if ($row['field_type'] == 'wygwam')
					{
						$config_id = $field_items_decoded['config'];
						$ft_info = $this->_get_wygwam_file_dir($config_id);
					}

					// Grid (EE 2.7)
					if ($row['field_type'] == 'grid')
					{
						$config_id = $row['field_id'];
						$grid_query = $this->EE->db->select('col_id, col_name, col_label, col_type, col_required, col_search, col_settings')
											->from('exp_grid_columns')
											->where('field_id', $config_id)
											->get();

						foreach($grid_query->result_array() as $grid_row)
						{
							$grid_file_directory = '';
							if ($grid_row['col_type'] == 'file')
							{
								$grid_file_directory = '<br /><span class="di_font-smaller">&nbsp;&nbsp;&nbsp;';
								$grid_file_dir_decoded = json_decode($grid_row['col_settings'],true);

								if (array_key_exists('allowed_directories', $grid_file_dir_decoded))
								{
									$grid_file_dir_id = $grid_file_dir_decoded['allowed_directories'];

									if ($grid_file_dir_id != 'all')
									{
										$grid_file_directory .= $this->_get_file_dir($grid_file_dir_id);
									}
									else
									{
										$grid_file_directory .= lang('all');
									}
								}
								else
								{
									$grid_file_directory .= lang('all');
								}
							}

							$ft_info .= '<strong>' . $grid_row['col_label'] . '</strong>' . $grid_file_directory . '<br /><input type="text" class="di_short_name" onFocus="this.select()" value="{' . $grid_row['col_name'] . '}" />';
							$ft_info .= '<br /><span class="di_font-smaller">&nbsp;&nbsp;&nbsp;' . $grid_row['col_type'];
							if ($grid_row['col_search'] == 'y') $ft_info .= '&nbsp;&nbsp;|&nbsp;&nbsp;' . lang('pt_matrix_searchable');
							if ($grid_row['col_required'] == 'y') $ft_info .= '&nbsp;&nbsp;|&nbsp;&nbsp;' . lang('pt_matrix_required');
							$ft_info .= ' ('. $grid_row['col_id'] .')';
							$ft_info .= '</span><br /><br />';
						}
					}


					// Get Matrix details
					if ($row['field_type'] == 'matrix')
					{
						$config_id = $row['field_id'];
						$matrix_query = $this->EE->db->select('col_id, col_name, col_label, col_type, col_required, col_search, col_settings')
											->from('exp_matrix_cols')
											->where('field_id', $config_id)
											->get();

						foreach($matrix_query->result_array() as $matrix_row)
						{
							$matrix_file_directory = '';
							if ($matrix_row['col_type'] == 'file')
							{
								$matrix_file_directory = '<br /><span class="di_font-smaller">&nbsp;&nbsp;&nbsp;';
								$matrix_file_dir_decoded = unserialize(base64_decode($matrix_row['col_settings']));
								if (array_key_exists('directory', $matrix_file_dir_decoded))
								{
									$matrix_file_dir_id = $matrix_file_dir_decoded['directory'];

									if ($matrix_file_dir_id != 'all')
									{
										$matrix_file_directory .= $this->_get_file_dir($matrix_file_dir_id);
									}
									else
									{
										$matrix_file_directory .= lang('all');
									}
								}
								else
								{
									$matrix_file_directory .= lang('all');
								}
							}
							if ($matrix_row['col_type'] == 'wygwam')
							{
								$matrix_wywgwam_config_decoded = unserialize(base64_decode($matrix_row['col_settings']));
								$matrix_wywgwam_config_id = $matrix_wywgwam_config_decoded['config'];
								if ($this->_check_pt_wygwam())
								{
									$matrix_wygwam_query = $this->_get_wygwam_config($config_id);
									if (array_key_exists('settings', $matrix_wygwam_query))
									{
										$matrix_wygwam_settings_decoded = unserialize(base64_decode($matrix_wygwam_query->row('settings')));
										$matrix_wygwam_file_id = $matrix_wygwam_settings_decoded['upload_dir'];
										$matrix_file_directory .= $this->_get_wygwam_file_dir($matrix_wygwam_file_id, '&nbsp;&nbsp;&nbsp;');
									}
								}
								$matrix_file_directory .= '</span>';
							}

							$ft_info .= '<strong>' . $matrix_row['col_label'] . '</strong>' . $matrix_file_directory . '<br /><input type="text" class="di_short_name" onFocus="this.select()" value="{' . $matrix_row['col_name'] . '}" />';
							$ft_info .= '<br /><span class="di_font-smaller">&nbsp;&nbsp;&nbsp;' . $matrix_row['col_type'];
							if ($matrix_row['col_search'] == 'y') $ft_info .= '&nbsp;&nbsp;|&nbsp;&nbsp;' . lang('pt_matrix_searchable');
							if ($matrix_row['col_required'] == 'y') $ft_info .= '&nbsp;&nbsp;|&nbsp;&nbsp;' . lang('pt_matrix_required');
							$ft_info .= ' ('. $matrix_row['col_id'] .')';
							$ft_info .= '</span><br /><br />';
						}
					}

					// Get Assets details
					if ($row['field_type'] == 'assets')
					{
						if (array_key_exists('filedirs', $field_items_decoded))
						{
							$ft_info = $this->_get_file_dir_array($field_items_decoded['filedirs']);
						}
						else
						{
							$ft_info = lang('no_file_upload');
						}
						
					}

					// Get DevDemon's Channel Images details
					if ($row['field_type'] == 'channel_images')
					{
						$ft_info = '';
						$ci_groups = array();
						$ci_groups = $field_items_decoded['channel_images']['action_groups'];
						$ci_location = $field_items_decoded['channel_images']['upload_location'];
						foreach ($ci_groups as $ci_action_group)
						{
							$ci_group_name = $ci_action_group['group_name'];
							$ci_group_actions = $ci_action_group['actions'];
							$ci_action_name = '';
							foreach ($ci_group_actions as $ci_actions=>$value)
							{
									$thisname = $ci_actions;
									$ci_action_name .= '&nbsp;&nbsp;&nbsp;' . ucwords(str_replace("_", " ", $thisname));
									if ($thisname == 'resize_adaptive' || $thisname == 'resize' || $thisname == 'crop_center')
									{
										$ci_action_width = $value['width'];
										$ci_action_height = $value['height'];
										$ci_action_name = $ci_action_name . ' (' . $ci_action_width . 'x' . $ci_action_height . ')';
									}
									if ($thisname == 'crop_standard')
									{
										$ci_action_width = $value['width'];
										$ci_action_height = $value['height'];
										$ci_start_x = $value['start_x'];
										$ci_start_y = $value['start_y'];
										$ci_action_name = $ci_action_name . ' (' . $ci_action_width . 'x' . $ci_action_height . ' - ' . lang('ci_start_x') . ': ' . $ci_start_x . ', ' . lang('ci_start_y') . ': ' .$ci_start_y . ')';
									}
									if ($thisname == 'rotate')
									{
										$ci_action_degrees = $value['degrees'];
										$ci_action_name = $ci_action_name . ' (' . $ci_action_degrees. ' ' . lang('ci_degrees') . ')';
									}
									if ($thisname == 'flip')
									{
										$ci_action_axis = $value['axis'];
										$ci_action_name = $ci_action_name . ' (' . $ci_action_axis . ')';
									}
									if ($thisname == 'resize_percent_adaptive')
									{
										$ci_action_width = $value['width'];
										$ci_action_height = $value['height'];
										$ci_action_pct = $value['percent'];
										$ci_action_name = $ci_action_name . ' (' . $ci_action_width . 'x' . $ci_action_height . ', ' . $ci_action_pct . '%)';
									}
									if ($thisname == 'resize_percent')
									{
										$ci_action_pct = $value['percent'];
										$ci_action_name = $ci_action_name . ' (' . $ci_action_pct . '%)';
									}
									$ci_action_name .= '<br />';
							}
							$ft_info .= ucwords($ci_action_group['group_name']). '<br /><span class="di_font-smaller">' .$ci_action_name. '</span><br />';
						}
						if ($ci_location == 'local')
						{
							$ci_dir_id = $field_items_decoded['channel_images']['locations']['local']['location'];
							$ci_location = $this->_get_file_dir($ci_dir_id);
						}
						$ft_info .= $ci_location;

					}

					// Get DevDemon's Channel Files details
					if ($row['field_type'] == 'channel_files')
					{
						$ft_info = '';
						$cf_location = $field_items_decoded['channel_files']['upload_location'];
						if ($cf_location == 'local')
						{
							$cf_dir_id = $field_items_decoded['channel_files']['locations']['local']['location'];
							$cf_location = $this->_get_file_dir($cf_dir_id);
						}
						$ft_info .= $cf_location;
					}

					//// MOVING ON ////

					// Complete the row
					$i = 1-$i;
					$class = "di_row$i";

					$c .= '<tr class="' . $class . '">';
					$c .= '<td>' .$row['field_id']. '</td>';
					$c .= '<td>' .$row['field_order']. '</td>';
					$c .= '<td><a href="' . $edit_field_url . '">' .$row['field_label']. '</a></td>';
					$c .= '<td><input type="text" class="di_short_name" onFocus="this.select()" value="{' .$row['field_name']. '}" /></td>';
					$c .= '<td>' .$row['field_type']. '</td>';
					$c .= '<td>';
					$c .= $ft_info;
					$c .= '</td>';
					$c .= '<td>' .$row['field_fmt']. '</td>';
					$c .= '<td>';
					$c .= ($row['field_required'] == 'y') ? lang('yes') : lang('no');
					$c .= '</td>';
					$c .= '<td>';
					$c .= ($row['field_search'] == 'y') ? lang('yes') : lang('no');
					$c .= '</td>';
					$c .= '<td>';
					$c .= ($row['field_is_hidden'] == 'n') ? lang('yes') : lang('no');
					$c .= '</td>';
					$c .= '</tr>';
				}
			}
		}
		return $c;
	}

	// ********************************************************************************* //
	/* Show each channel field SQL query  */
	function _generate_query($group_id, $channel_id)
	{
		$c='';
		$c .= "<textarea rows='2' style='width:900px;font-size:11px;' onFocus='this.select()'>";
		$c .= "SELECT entry_id, channel_id";

		$channel_fields = $this->EE->db->select('field_id')
						->from('exp_channel_fields')
						->where('group_id', $group_id)
						->where('site_id', $this->_site_id)
						->order_by('field_order', 'asc')
						->get();

	    foreach ($channel_fields->result_array() AS $row)
	    {
	    	//if (array_key_exists('field_id', $channel_fields)) {
	    		$c .= ", field_id_";
				$c .= $row['field_id'];
			//}
	    }
	    $c .= " FROM exp_channel_data WHERE channel_id = ";
		$c .= $channel_id;
		$c .= "</textarea>";

		return $c;
	}

	// ********************************************************************************* //
	/* Show each channel field SQL query with JOIN on channel titles  */
	function _generate_full_query($group_id, $channel_id)
	{
		$c='';
		$c .= "<textarea rows='2' style='width:900px;font-size:11px;' onFocus='this.select()'>";
		$c .= "SELECT t.title AS 'Title', d.entry_id AS 'Entry ID'";

		$channel_fields = $this->EE->db->select('field_id,field_label')
						->from('exp_channel_fields')
						->where('group_id', $group_id)
						->where('site_id', $this->_site_id)
						->order_by('field_order', 'asc')
						->get();

	    foreach ($channel_fields->result_array() AS $row)
	    {
	    	//if (array_key_exists('field_id', $channel_fields)) {
	    		$c .= ", d.field_id_";
				$c .= $row['field_id'];
				$c .= " AS '";
				$c .= $row['field_label'];
				$c .= "'";
			//}
	    }
	    $c .= " FROM exp_channel_data d";
		$c .= " INNER JOIN exp_channel_titles t ON t.entry_id = d.entry_id";
	    $c .= " WHERE d.channel_id = ";
		$c .= $channel_id;
		$c .= "</textarea>";

		return $c;
	}

	// ********************************************************************************* //
	/* Show each template information for Template View */
	function _display_templates($template_group_id)
	{
		$c='';

		$templates_files = $this->EE->db->select('template_id, template_name, save_template_file, template_type, cache, allow_php, php_parse_location')
								->from('exp_templates')
								->where('group_id', $template_group_id)
								->where('site_id', $this->_site_id)
								->order_by('template_name', 'asc')
								->get();

		if ($templates_files->num_rows > 0)
		{
			$i = 0;
			foreach($templates_files->result_array() as $row)
			{
				$edit_field_url = BASE . AMP . 'C=design' . AMP . 'M=edit_template' . AMP . 'id=' . $row['template_id'];
				$i = 1-$i;
				$class = "di_row$i";

				$c .= '<tr class="' . $class . '">';
				$c .= '<td width="35%"><a href="' . $edit_field_url . '">' .$row['template_name']. '</a></td>';
				$c .= '<td  width="20%">' .$row['template_type']. '</td>';
				$c .= '<td  width="10%">';
				$c .= ($row['save_template_file'] == 'y') ? lang('yes') : lang('no');
				$c .= '</td>';
				$c .= '<td width="10%">';
				$c .= ($row['cache'] == 'y') ? lang('yes') : lang('no');
				$c .= '</td>';
				$c .= '<td width="10%">';
				if ($row['allow_php'] == 'y')
				{
					$c .= lang('yes') . ' (' . ($row['php_parse_location'] == 'o' ? lang('output') : lang('input')) . ')';
				}
				else
				{
					$c .= lang('no');
				}
				$c .= '</td>';
				$c .= '</tr>';

			}
		}
		return $c;
	}

	// ********************************************************************************* //
	/* Show each channel field SQL query  */
	function _generate_template_query($template_group_id)
	{
		$c='';
		$c .= "<textarea rows='2' style='width:900px;font-size:11px;' onFocus='this.select()'>";
		$c .= "SELECT t.template_id AS 'Template ID', t.template_name AS 'Template Name', t.template_data AS 'Template', g.group_name AS 'Template Group'";
	    $c .= " FROM exp_templates t";
		$c .= " INNER JOIN exp_template_groups g ON g.group_id = t.group_id";
		$c .= " WHERE t.group_id = ";
		$c .= $template_group_id;
		$c .= "</textarea>";

		return $c;
	}

} // END CLASS

/* End of file developer_info_helper.php  */
/* Location: ./system/expressionengine/third_party/developer_info/libraries/developer_info_helper.php */