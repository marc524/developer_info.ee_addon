<?php
	echo '<div class="jump-nav">'; echo lang('jump_to'); echo $all_channels; echo '</div>'; echo $site_id;
?>
	<ul id="action_nav">
		<li class="button"><?= $channels_new_btn; ?></li>
		<li class="button"><?= $channels_new_fg_btn; ?></li>
		<li class="button"><?= $channels_new_stats_btn; ?></li>
		<li class="button"><?= $channels_new_cats_btn; ?></li>
	</ul>
	<ul id="action_sub_nav">
		<li><?= $channels_all_fg_btn; ?></li>
		<?= $wygwam_configs_btn; ?>
	</ul>
<?
				foreach($channels as $channel)
				{
?>
				<table class="mainTable" id="<?= $channel['channel_id']; ?>" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th colspan="8" class="di_channel-heading">
								<span class="di_channel-links"><?= $channel['channel_edit_entries']; ?> | <?= $channel['chnnel_edit_preferences']; ?> | <?= $channel['channel_edit_groups']; ?><? if(!empty($channel['field_group_id'])){ ?> | <?= $channel['channel_edit_fg']; }?><? if(!empty($channel['field_group_id'])){ ?> | <?= $channel['channel_new_field']; }?></span>
								<h2><?= $channel['channel_title']; ?></h2><br />
								Short name: <?= $channel['channel_name']; ?><br />
								Channel id: <?= $channel['channel_id']; ?>
							</th>
						</tr>
					</thead>

				<tbody>

					<tr class="di_channel-info">
						<td colspan="8">
							<div class="di_col">
								<div class="di_col_head"><?=lang('info_cats'); ?>:</div>
								<div class="di_categories-link"><?=lang('show_cats'); ?></div>
								<div class="di_link">
									<?= $channel['categories']; ?>
								</div>
							</div>

							<div class="di_col">
								<div class="di_col_head"><?=lang('info_stats'); ?>:</div>
								<div class="di_status-link"><?=lang('show_stats'); ?></div>
								<div class="di_link">
									<?= $channel['statuses']; ?>
								</div>
							</div>

							<div class="di_col">
								<div class="di_col_head"><?=lang('info_fgs'); ?>:</div>
								<div>
									<?= $channel['field_group_name']; ?>
								</div>
							</div>
						</td>
					</tr>
					<tr class="di_channel-fields-info-header">
						<td width="5%"><?=lang('channel_field_id'); ?></td>
						<td width="15%"><?=lang('channel_field_label'); ?></td>
						<td width="15%"><?=lang('channel_field_name'); ?></td>
						<td width="10%"><?=lang('channel_field_type'); ?></td>
						<td width="25%"><?=$dir_col_head; ?></td>
						<td width="10%"><?=lang('channel_field_format'); ?></td>
						<td width="5%"><?=lang('channel_field_mandatory'); ?></td>
						<td width="5%"><?=lang('channel_field_searchable'); ?></td>
					</tr>
					<?= $channel['channel_fields']; ?>
					<tr class="di_channel-info">
						<td colspan="8" class="font-smaller">
								<div class="di_query-link"><?=lang('show_query'); ?></div>
								<div class="di_link">
									<?= $channel['channel_query']; ?>
								</div>
						</td>
					</tr>
				</tbody>
			</table>
<?
				}
?>
<a href="#top"><strong>Scroll to top</strong></div>
<? ?>