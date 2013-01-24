<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	echo '<div id="di_jump-nav">'; echo lang('jump_to'); echo $all_channels; echo '</div>'; echo $site_id;
?>
	<script type="text/javascript">jQuery(document).ready(function() { setChannelClass(); });</script>

	<div id="di_expand-controls" class="di_font-two-smaller"><a href="#" class="di_collapse-channel"><?= lang('hide_all'); ?></a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="#" class="di_expand-channel"><?= lang('show_all'); ?></a></div>
	
	<ul id="action_nav">
		<li class="button"><?= $channels_new_btn; ?></li>
		<li class="button"><?= $channels_new_fg_btn; ?></li>
		<li class="button"><?= $channels_new_stats_btn; ?></li>
		<li class="button"><?= $channels_new_cats_btn; ?></li>
	</ul>
	<ul id="di_action_sub_nav">
		<li><?= $channels_all_fg_btn; ?></li>
		<?= $wygwam_configs_btn; ?>
	</ul>
<?php
	foreach($channels as $channel) :
?>
		<table class="mainTable" id="<?= $channel['channel_id']; ?>" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th colspan="10" class="di_channel-heading">
						<h2 class="di_head"><?= $channel['channel_title']; ?></h2>
						<p class="di_channel-links"><?= $channel['channel_edit_entries']; ?> | <?= $channel['channel_edit_preferences']; ?> | <?= $channel['channel_edit_groups']; ?><?php if(!empty($channel['field_group_id'])){ ?>
						<br /><?= $channel['channel_edit_fg']; } ?><?php if(!empty($channel['field_group_id'])){ ?> | <?= $channel['channel_new_field']; }?></p>
						<p class="di_left-info"><?= lang('channel_short'); ?>: <input type="text" class="di_short_name" onFocus="this.select()" value="<?= $channel['channel_name']; ?>">
						<br /><?= lang('channel_id'); ?>: <?= $channel['channel_id']; ?></p>
						<div class="di_font-two-smaller"><a href="#" title="channel_<?= $channel['channel_id']; ?>" class="di_channel-showhide channel_<?= $channel['channel_id']; ?>"><?= lang('channel_hide'); ?></a></div>
					</th>
				</tr>
			</thead>
			<tbody id="channel_<?= $channel['channel_id']; ?>" class="open">
				<tr class="di_channel-info">
					<td colspan="10">
						<div class="di_col">
							<div class="di_col_head"><?= lang('info_cats'); ?>:</div>
							<div class="di_categories-link"><?= lang('show_cats'); ?></div>
							<div class="di_link">
								<?= $channel['categories']; ?>
							</div>
						</div>

						<div class="di_col">
							<div class="di_col_head"><?= lang('info_stats'); ?>:</div>
							<div class="di_status-link"><?= lang('show_stats'); ?></div>
							<div class="di_link">
								<?= $channel['statuses']; ?>
							</div>
						</div>

						<div class="di_col">
							<div class="di_col_head"><?= lang('info_fgs'); ?>:</div>
							<div>
								<?= $channel['field_group_name']; ?>
							</div>
						</div>
					</td>
				</tr>
				<tr class="di_channel-fields-info-header">
					<td width="5%"><?= lang('channel_field_id'); ?></td>
					<td width="5%"><?= lang('channel_order_numb'); ?></td>
					<td width="15%"><?= lang('channel_field_label'); ?></td>
					<td width="15%"><?= lang('channel_field_name'); ?></td>
					<td width="10%"><?= lang('channel_field_type'); ?></td>
					<td width="25%"><?= $dir_col_head; ?></td>
					<td width="10%"><?= lang('channel_field_format'); ?></td>
					<td width="5%"><?= lang('channel_field_mandatory'); ?></td>
					<td width="5%"><?= lang('channel_field_searchable'); ?></td>
					<td width="5%"><?= lang('channel_field_viewable'); ?></td>
				</tr>
				<?= $channel['channel_fields']; ?>
				<tr class="di_channel-info">
					<td colspan="10" class="di_font-smaller">
							<div class="di_query-link"><?= lang('show_query'); ?></div>
							<div class="di_link">
								<?= $channel['channel_display_query']; ?>
							</div>
							
							
							<div class="di_full-query-link"><?= lang('show_full_query'); ?></div>
							<div class="di_link">
								<?= $channel['channel_display_full_query']; ?>
							</div>
					</td>
				</tr>
			</tbody>
		</table>
<?php
	endforeach
?>
<div style="margin-top:10px;"><a href="#top"><strong>Scroll to top</strong></a></div>