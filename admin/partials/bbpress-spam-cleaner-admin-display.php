<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://stickybitsoftware.com
 * @since      0.0.1
 *
 * @package    bbPress_Spam_Cleaner
 * @subpackage bbPress_Spam_Cleaner/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

	<h2>bbPress Spam Cleaner</h2>
	<h3>bbPress Spam Cleaner Actions</h3>
	<form class="settings" method="post" action="">
		<fieldset class="submit">
			<input class="button-primary" type="submit" name="submit" value="Scan Forums for Spam" />
			<?php wp_nonce_field( 'bbpress-spam-cleaner-scan' ); ?>
		</fieldset>
	</form>
	<?php if(count($this->posts)): ?>
	<table class="widefat" cellspacing="0">
		<thead>
			<tr>

				<th id="cb" class="manage-column column-cb check-column" scope="col">Select</th>
				<th id="columnname" class="manage-column column-columnname num" scope="col">ID</th>
				<th id="columnname" class="manage-column column-columnname" scope="col">Title</th>
				<th id="columnname" class="manage-column column-columnname" scope="col">Content</th>
				<th id="columnname" class="manage-column column-columnname" scope="col">Spam?</th>

			</tr>
		</thead>

		<tfoot>
			<tr>

				<th class="manage-column column-cb check-column" scope="col">Select</th>
				<th class="manage-column column-columnname num" scope="col">ID</th>
				<th class="manage-column column-columnname" scope="col">Title</th>
				<th class="manage-column column-columnname" scope="col">Content</th>
				<th class="manage-column column-columnname" scope="col">Spam?</th>

			</tr>
		</tfoot>

		<tbody>
			<?php
			 $count = 0;
			 foreach($this->posts as $p) : 
			?>
			<tr class="<?php echo (++$count%2 ? '' : 'alternate') ?>">
				<th class="check-column" scope="row"></td>
				<td class="column-columnname"><?php echo ($p['id']) ?></td>
				<td class="column-columnname"><?php echo ($p['title']) ?></td>
				<td class="column-columnname"><?php echo ($p['content']) ?></td>
				<td class="column-columnname"><?php echo ($p['spam_status'] ? 'YES' : 'NO') ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>