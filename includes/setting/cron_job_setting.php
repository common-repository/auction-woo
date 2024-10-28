<?php
add_settings_section(
	'devsoul_bid_cron_settings_fields',
	'', // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'devsoul_bid_cron_settings_sections'
);
add_settings_field(
	'af_bid_allow_user',
	esc_html__( 'Show auctions on the shop page', ' devsoul_bfwcac_text_d' ), // The label.
	'af_bid_allow_user',
	'devsoul_bid_cron_settings_sections', // The page on which this option will be displayed.
	'devsoul_bid_cron_settings_fields'
);
register_setting(
	'devsoul_bid_cron_settings_fields',
	'af_bid_allow_user'
);


add_settings_field(
	'send_email_to_bidders',
	esc_html__( 'Show auctions on the shop page', ' devsoul_bfwcac_text_d' ), // The label.
	'send_email_to_bidders',
	'devsoul_bid_cron_settings_sections', // The page on which this option will be displayed.
	'devsoul_bid_cron_settings_fields'
);
register_setting(
	'devsoul_bid_cron_settings_fields',
	'send_email_to_bidders'
);

add_settings_field(
	'ct_bid_cron_job_type',
	esc_html__( 'Select cron type', ' devsoul_bfwcac_text_d' ), // The label.
	'ct_bid_cron_job_type',
	'devsoul_bid_cron_settings_sections', // The page on which this option will be displayed.
	'devsoul_bid_cron_settings_fields'
);
register_setting(
	'devsoul_bid_cron_settings_fields',
	'ct_bid_cron_job_type'
);


add_settings_field(
	'ct_bid_set_time',
	esc_html__( 'Set time', ' devsoul_bfwcac_text_d' ), // The label.
	'ct_bid_set_time',
	'devsoul_bid_cron_settings_sections', // The page on which this option will be displayed.
	'devsoul_bid_cron_settings_fields'
);
register_setting(
	'devsoul_bid_cron_settings_fields',
	'ct_bid_set_time'
);
function ct_bid_set_time() {
	$ct_bid_set_time = get_option('ct_bid_set_time');
	?>
	<input type="number" id="ct_bid_set_time" name="ct_bid_set_time" value="<?php echo esc_attr($ct_bid_set_time); ?>" min="0">
	<?php
}

function ct_bid_cron_job_type() {
	$ct_bid_cron_job_type = get_option('ct_bid_cron_job_type');
	?>
	<select id="ct_bid_cron_job_type" name="ct_bid_cron_job_type" class="ct_bid_cron_job_type">
		<option value="seconds" <?php selected($ct_bid_cron_job_type, 'seconds'); ?>>
			<?php echo esc_html__('Seconds', 'woocommerce'); ?>
		</option>
		<option value="days" <?php selected($ct_bid_cron_job_type, 'days'); ?>>
			<?php echo esc_html__('Days', 'woocommerce'); ?>
		</option>
		<option value="hours" <?php selected($ct_bid_cron_job_type, 'hours'); ?>>
			<?php echo esc_html__('Hours', 'woocommerce'); ?>
		</option>
		<option value="minutes" <?php selected($ct_bid_cron_job_type, 'minutes'); ?>>
			<?php echo esc_html__('Minutes', 'woocommerce'); ?>
		</option>
	</select>
	<p class="description"><?php echo esc_html__('Choose which cron format you want to use', 'woocommerce'); ?></p>
	<?php
}

function send_email_to_bidders() {
	$send_email_to_bidders = get_option('send_email_to_bidders');
	?>
	<select id="send_email_to_bidders" name="send_email_to_bidders" class="wc-enhanced-select">
		<option value="notify_new_bid" <?php selected($send_email_to_bidders, 'notify_new_bid'); ?>>
			<?php echo esc_html__('To notify any new bid', 'woocommerce'); ?>
		</option>
		<option value="bid_about_to_end" <?php selected($send_email_to_bidders, 'bid_about_to_end'); ?>>
			<?php echo esc_html__('When the auction is about to end', 'woocommerce'); ?>
		</option>
		<option value="lose_the_bid" <?php selected($send_email_to_bidders, 'lose_the_bid'); ?>>
			<?php echo esc_html__('When they lose the auction', 'woocommerce'); ?>
		</option>
		<option value="bid_close_by_now" <?php selected($send_email_to_bidders, 'bid_close_by_now'); ?>>
			<?php echo esc_html__('When the auction is closed by buy now', 'woocommerce'); ?>
		</option>
	</select>
	<p class="description"><?php echo esc_html__('When auction is closed', 'woocommerce'); ?></p>
	<?php
}

function af_bid_allow_user() {
	$af_bid_allow_user = get_option('af_bid_allow_user');
	?>
	<input type="checkbox" id="af_bid_allow_user" name="af_bid_allow_user" 
	<?php
	if (!empty($af_bid_allow_user)) {
		echo 'checked';
	}
	?>
	>
	<p class="description"><?php echo esc_html__('If enabled, users can leave their email in the auction page and receive a notification when the auction is about to end', 'woocommerce'); ?></p>
	<?php
}
