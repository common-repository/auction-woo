<?php
add_settings_section(
	'devsoul_bid_customize_settings_fields',
	'', // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'devsoul_bid_customize_settings_sections'
);
add_settings_field(
	'ct_auction_badge',
	esc_html__('Show auction badge on product image', ' devsoul_bfwcac_text_d'), // The label.
	'ct_auction_badge',
	'devsoul_bid_customize_settings_sections', // The badge on which this option will be displayed.
	'devsoul_bid_customize_settings_fields'
);
register_setting(
	'devsoul_bid_customize_settings_fields',
	'ct_auction_badge'
);
add_settings_field(
	'ct_upload_auction_badge',
	esc_html__('Show auction badge on product image', ' devsoul_bfwcac_text_d'), // The label.
	'ct_upload_auction_badge',
	'devsoul_bid_customize_settings_sections', // The badge on which this option will be displayed.
	'devsoul_bid_customize_settings_fields'
);
register_setting(
	'devsoul_bid_customize_settings_fields',
	'ct_upload_auction_badge'
);
add_settings_field(
	'ct_end_date_of_auction',
	esc_html__('Show end date of auctions on product page', ' devsoul_bfwcac_text_d'), // The label.
	'ct_end_date_of_auction',
	'devsoul_bid_customize_settings_sections', // The badge on which this option will be displayed.
	'devsoul_bid_customize_settings_fields'
);
register_setting(
	'devsoul_bid_customize_settings_fields',
	'ct_end_date_of_auction'
);


add_settings_field(
	'ct_count_down',
	esc_html__('Show Countdown', ' devsoul_bfwcac_text_d'), // The label.
	'ct_count_down',
	'devsoul_bid_customize_settings_sections', // The badge on which this option will be displayed.
	'devsoul_bid_customize_settings_fields'
);
register_setting(
	'devsoul_bid_customize_settings_fields',
	'ct_count_down'
);

add_settings_field(
	'countdown_text_color',
	esc_html__('Countdown Text color', ' devsoul_bfwcac_text_d'), // The label.
	'countdown_text_color',
	'devsoul_bid_customize_settings_sections', // The badge on which this option will be displayed.
	'devsoul_bid_customize_settings_fields'
);
register_setting(
	'devsoul_bid_customize_settings_fields',
	'countdown_text_color'
);

add_settings_field(
	'countdown_background_color',
	esc_html__('Section Background', ' devsoul_bfwcac_text_d'), // The label.
	'countdown_background_color',
	'devsoul_bid_customize_settings_sections', // The badge on which this option will be displayed.
	'devsoul_bid_customize_settings_fields'
);
register_setting(
	'devsoul_bid_customize_settings_fields',
	'countdown_background_color'
);

add_settings_field(
	'countdown_blocks_background',
	esc_html__('Blocks Background', ' devsoul_bfwcac_text_d'), // The label.
	'countdown_blocks_background',
	'devsoul_bid_customize_settings_sections', // The badge on which this option will be displayed.
	'devsoul_bid_customize_settings_fields'
);
register_setting(
	'devsoul_bid_customize_settings_fields',
	'countdown_blocks_background'
);

add_settings_field(
	'auction_color_when_end',
	esc_html__('When auction is about to end', ' devsoul_bfwcac_text_d'), // The label.
	'auction_color_when_end',
	'devsoul_bid_customize_settings_sections', // The badge on which this option will be displayed.
	'devsoul_bid_customize_settings_fields'
);
register_setting(
	'devsoul_bid_customize_settings_fields',
	'auction_color_when_end'
);

add_settings_field(
	'when_there_is_less_than',
	esc_html__('Set Time', ' devsoul_bfwcac_text_d'), // The label.
	'when_there_is_less_than',
	'devsoul_bid_customize_settings_sections', // The badge on which this option will be displayed.
	'devsoul_bid_customize_settings_fields'
);
register_setting(
	'devsoul_bid_customize_settings_fields',
	'when_there_is_less_than'
);


function ct_auction_badge() {
	$ct_auction_badge = get_option('ct_auction_badge');
	?>
	<input type="checkbox" name="ct_auction_badge" value="
	<?php
	if (!empty($ct_auction_badge)) {
		echo 'checked';
	}
	?>
	">
	<p class="description">
		<?php echo esc_html__('Enable to show a badge to identify the auctions product', 'woocommerce'); ?>
	</p>
	<?php
}
function ct_upload_auction_badge() {
	$ct_upload_auction_badge = get_option('ct_upload_auction_badge');
	?>
	<input type="file" name="ct_upload_auction_badge" value="<?php echo esc_attr($ct_upload_auction_badge); ?>">
	<p class="description">
		<?php echo esc_html__('Upload a graphic badge to identify the auctions products', 'woocommerce'); ?>
	</p>
	<?php
}

function ct_end_date_of_auction() {
	$ct_end_date_of_auction = get_option('ct_end_date_of_auction');
	?>
	<input type="checkbox" name="ct_end_date_of_auction" value="
	<?php
	if (!empty($ct_end_date_of_auction)) {
		echo 'checked';
	}
	?>
	">
	<p class="description">
		<?php echo esc_html__('Enable to show a badge to identify the auctions product', 'woocommerce'); ?>
	</p>
	<?php
}
function ct_count_down() {
	$ct_count_down = get_option('ct_count_down');
	?>
	<input type="checkbox" name="ct_count_down" value="
	<?php
	if (!empty($ct_count_down)) {
		echo 'checked';
	}
	?>
	">
	<p><?php echo esc_html__('Enable to show the countdown'); ?></p>
	<?php
}

function countdown_text_color() {
	$countdown_text_color = get_option('countdown_text_color');
	?>
	<input type="color" name="countdown_text_color" value="<?php echo esc_attr($countdown_text_color); ?>">
	<p><?php echo esc_html__('countdown text color'); ?></p>


	<?php
}

function countdown_background_color() {
	$countdown_background_color = get_option('countdown_background_color');
	?>
	<input type="color" name="countdown_background_color" value="<?php echo esc_attr($countdown_background_color); ?>">
	<p><?php echo esc_html__('countdown  Background color'); ?></p>
	<?php
}

function countdown_blocks_background() {
	$countdown_blocks_background = get_option('countdown_blocks_background');
	?>
	<input type="color" name="countdown_blocks_background" value="<?php echo esc_attr($countdown_blocks_background); ?>">
	<p><?php echo esc_html__('countdown section  Background color'); ?></p>
	<?php
}

function auction_color_when_end() {
	$auction_color_when_end = get_option('auction_color_when_end');
	?>
	<input type="color" name="auction_color_when_end" value="<?php echo esc_attr($auction_color_when_end); ?>">
	<p><?php echo esc_html__('countdown text color bidding is about to end'); ?></p>
	<?php
}

function when_there_is_less_than() {
	$when_there_is_less_than = get_option('when_there_is_less_than');
	?>
	<input type="number" name="when_there_is_less_than" value="<?php echo esc_attr($when_there_is_less_than); ?>">
	<p><?php echo esc_html__('Before the auction ends set time in minutes'); ?></p>
	<?php
}
