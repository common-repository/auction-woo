<?php

add_settings_section(
	'devsoul_bid_Bid_all_user_fields',
		'', // Title to be displayed on the administration page.
		'', // Callback used to render the description of the section.
		'devsoul_bid_user_offer_product_sections'
	);
$fields = array(
	'devsoul_bfw_first_name' => 'Enable First Name',
	'devsoul_bfw_last_name' => 'Enable Last Name',
	'devsoul_bfw_email_address' => 'Enable Email Address',
	'devsoul_bfw_phone_number' => 'Enable Phone Number',
	'devsoul_bfw_product_year' => 'Enable Product Year',
	'devsoul_bfw_product_make' => 'Enable Product Make',
	'devsoul_bfw_product_model' => 'Enable Product Model',
	'devsoul_bfw_product_type' => 'Enable Product Type',
	'devsoul_bfw_product_state' => 'Enable Product State',
	'devsoul_bfw_product_city' => 'Enable Product City',
	'devsoul_bfw_dealer' => 'Enable Dealer',
	'devsoul_bfw_product_videos' => 'Enable Product Videos',
	'devsoul_bfw_product_details' => 'Enable Product Details',
	'devsoul_bfw_product_photos' => 'Enable Product Photos',
	'devsoul_bfw_accept_terms' => 'Enable Accept Terms',
);

	// Register settings and fields
foreach ($fields as $field_id => $label) {
	add_settings_field(
		$field_id,
		esc_html__($label, 'devsoul_bfwcac_text_d'),
		'af_bid_render_checkbox_' . $field_id,
		'devsoul_bid_user_offer_product_sections',
		'devsoul_bid_Bid_all_user_fields',
		array(
			'label_for' => $field_id,
			'description' => 'Enable checkbox to show ' . strtolower(str_replace('Enable ', '', $label)) . ' to user',
		)
	);
	register_setting(
		'devsoul_bid_user_offer_product_fields',
		$field_id
	);
}

// Render functions for each checkbox field
function af_bid_render_checkbox( $args ) {
	$option = get_option($args['label_for']);
	?><input type="checkbox" name="<?php echo esc_attr($args['label_for']); ?> " value="1" <?php checked(1, $option, false); ?>>
	<?php
	if (isset($args['description'])) {
		?>
		<p class="description"><?php echo esc_html__($args['description'], 'devsoul_bfwcac_text_d'); ?></p>
	<?php
	}
}

function af_bid_render_checkbox_devsoul_bfw_first_name( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_last_name( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_email_address( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_phone_number( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_product_year( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_product_make( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_product_model( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_product_type( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_product_state( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_product_city( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_dealer( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_product_videos( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_product_details( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_product_photos( $args ) {
	af_bid_render_checkbox($args);
}

function af_bid_render_checkbox_devsoul_bfw_accept_terms( $args ) {
	af_bid_render_checkbox($args);
}
