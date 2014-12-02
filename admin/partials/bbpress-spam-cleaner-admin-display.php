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
	<form class="settings" method="post" action="">
		<fieldset class="submit">
			<input class="button-primary" type="submit" name="topic" value="Scan Topics" />
			<input class="button-primary" type="submit" name="reply" value="Scan Replies" />
			<input type="hidden" name="page" value="bbpress-spam-cleaner-scan" />
			<?php wp_nonce_field( 'bbpress-spam-cleaner-scan' ); ?>
		</fieldset>
	</form>
	<?php 
		if( ! class_exists( 'BBP_Spam_Cleaner_List_Table' ) ) {
			require_once ABSPATH . 'wp-content/plugins/bbpress-spam-cleaner/includes/class-bbpress-spam-cleaner-list-table.php';
		}
		echo("<div class='list-table'>");
		$list_table = new BBP_Spam_Cleaner_List_Table();
		$list_table->prepare_items();
		echo("<form method='post'>");
		echo("<input type='hidden' name='page' value='bbpress-spam-list-table' />");
		$list_table->display();
		echo("</form>");
		echo("</div>");
	?>
</div>