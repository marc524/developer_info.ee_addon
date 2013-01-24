<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	echo '<div id="di_jump-nav">'; echo lang('jump_to'); echo $all_templates; echo '</div>';
?>
  	<script type="text/javascript">jQuery(document).ready(function() { setTemplateClass(); });</script>

  	<div id="di_expand-controls" class="di_font-two-smaller di_template-controls"><a href="#" class="di_collapse-template"><?= lang('hide_all'); ?></a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="#" class="di_expand-template"><?= lang('show_all'); ?></a></div>
  
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
		<table class="mainTable" id="<?= $template['group_id']; ?>" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th colspan="10" class="di_channel-heading">
						<h2 class="di_head"><?= $template['group_name']; ?></h2>
						<p class="di_template-links"><?= $template['new_template']; ?></p>
						<p class="di_left-info"><?= lang('template_group_id'); ?>: <?= $template['group_id']; ?></p>
						
						<div class="di_font-two-smaller"><a href="#" title="template_<?= $template['group_id']; ?>" class="di_template-showhide tmpl_<?= $template['group_id']; ?>"><?= lang('template_hide'); ?></a></div>
					</th>
				</tr>
			</thead>
			<tbody id="template_<?= $template['group_id']; ?>" class="open">
				<tr class="di_channel-fields-info-header">
					<th width="35%"><?= lang('template_name'); ?></th>
					<th width="20%"><?= lang('template_type'); ?></th>
					<th width="10%"><?= lang('template_save_as_file'); ?></th>
					<th width="10%"><?= lang('template_caching'); ?></th>
					<th width="10%"><?= lang('template_php'); ?></th>
				</tr>
					<?= $template['template_info']; ?>					
				<tr class="di_channel-info">
					<td colspan="5">
						<div class="di_template-query-link"><?= lang('template_show_query'); ?></div>
						<div class="di_link">
							<?= $template['template_display_query']; ?>							
						</div>
					</td>
				</tr>
			</tbody>
		</table>

<?php
	endforeach
?>
<div id="di_scroll-templates"><a href="#top"><strong>Scroll to top</strong></a></div>