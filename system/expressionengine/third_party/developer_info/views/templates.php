<?php
	echo lang('jump_to'); echo $all_templates;
?>
	<ul id="action_nav">
		<li class="button"><?= $templates_mgr_btn; ?></li>
		<li class="button"><?= $templates_prefs_btn; ?></li>
		<li class="button"><?= $templates_new_btn; ?></li>
		<li class="button"><?= $templates_snippets_btn; ?></li>
		<li class="button"><?= $templates_glob_vars_btn; ?></li>
	</ul>
<?php
		foreach($templates as $template):
?>
			<h6 class="di"><?= $template['group_name']; ?></h6>
			<span class="di_template-links"><?= $template['new_template']; ?></span>
			<table class="mainTable" id="<?= $template['group_id']; ?>" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th width="35%"><?= lang('template_name'); ?></th>
						<th width="20%"><?= lang('template_type'); ?></th>
						<th width="10%"><?= lang('template_save_as_file'); ?></th>
						<th width="10%"><?= lang('template_caching'); ?></th>
						<th width="10%"><?= lang('template_php'); ?></th>
					</tr>
			</thead>
			<tbody>
					<?= $template['template_info']; ?>
			</tbody>
		</table>
<?php
	endforeach
?>
<a href="#top"><strong>Scroll to top</strong></div>