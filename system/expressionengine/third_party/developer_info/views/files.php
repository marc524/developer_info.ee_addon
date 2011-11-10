<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
	<ul id="action_nav">
		<li class="button"><?= $files_new_btn; ?></li>
		<li class="button"><?= $files_prefs_btn; ?></li>
		<li class="button"><?= $files_mgr_btn; ?></li>
	</ul>

			<table class="mainTable" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th width="5%"><?= lang('files_id'); ?></th>
						<th width="10%"><?= lang('files_name'); ?></th>
						<th width="20%"><?= lang('files_path'); ?></th>
						<th width="20%"><?= lang('files_url'); ?></th>
						<th width="10%"><?= lang('files_types'); ?></th>
						<th width="8%"><?= lang('files_max_size'); ?></th>
						<th width="8%"><?= lang('files_max_height'); ?></th>
						<th width="8%"><?= lang('files_max_width'); ?></th>
					</th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach($files as $file):
?>
				<tr>
					<td><?= $file['id']; ?></td>
					<td><?= $file['name']; ?></td>
					<td><?= $file['server_path']; ?></td>
					<td><?= $file['url']; ?></td>
					<td><?= $file['allowed_types']; ?></td>
					<td><?= $file['max_size']; ?></td>
					<td><?= $file['max_height']; ?></td>
					<td><?= $file['max_width']; ?></td>
				</tr>
<?php
	endforeach
?>
				</tbody>
			</table>