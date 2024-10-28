<?php
add_settings_section(
	'devsoul_bid_auction_page_settings_fields',
	'', // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'devsoul_bid_auction_page_settings_sections'
);
add_settings_field(
	'af_bid_auctions_product_page',
	esc_html__( 'Show auction badge on product image', ' devsoul_bfwcac_text_d' ), // The label.
	'af_bid_auctions_product_page',
	'devsoul_bid_auction_page_settings_sections', // The page on which this option will be displayed.
	'devsoul_bid_auction_page_settings_fields'
);
register_setting(
	'devsoul_bid_auction_page_settings_fields',
	'af_bid_auctions_product_page'
);

add_settings_section(
	'devsoul_bid_auction_page_settings_fields',
	'', // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'devsoul_bid_auction_page_settings_sections'
);
add_settings_field(
	'af_bid_show_product_stock',
	esc_html__( 'Show product qunatity', ' devsoul_bfwcac_text_d' ), // The label.
	'af_bid_show_product_stock',
	'devsoul_bid_auction_page_settings_sections', // The page on which this option will be displayed.
	'devsoul_bid_auction_page_settings_fields'
);
register_setting(
	'devsoul_bid_auction_page_settings_fields',
	'af_bid_show_product_stock'
);
add_settings_field(
	'af_bid_reseve_product_price',
	esc_html__( 'Show if the reserve price has been reached', ' devsoul_bfwcac_text_d' ), // The label.
	'af_bid_reseve_product_price',
	'devsoul_bid_auction_page_settings_sections', // The page on which this option will be displayed.
	'devsoul_bid_auction_page_settings_fields'
);
register_setting(
	'devsoul_bid_auction_page_settings_fields',
	'af_bid_reseve_product_price'
);

add_settings_field(
	'af_bid_auction_is_overtime',
	esc_html__( 'Show if the auction is in overtime', ' devsoul_bfwcac_text_d' ), // The label.
	'af_bid_auction_is_overtime',
	'devsoul_bid_auction_page_settings_sections', // The page on which this option will be displayed.
	'devsoul_bid_auction_page_settings_fields'
);
register_setting(
	'devsoul_bid_auction_page_settings_fields',
	'af_bid_auction_is_overtime'
);

add_settings_field(
	'ct_automatic_bid_type',
	esc_html__( 'In bid tab show', ' devsoul_bfwcac_text_d' ), // The label.
	'ct_automatic_bid_type',
	'devsoul_bid_auction_page_settings_sections', // The page on which this option will be displayed.
	'devsoul_bid_auction_page_settings_fields'
);
register_setting(
	'devsoul_bid_auction_page_settings_fields',
	'ct_automatic_bid_type'
);

function af_bid_auctions_product_page() {
	$af_bid_auctions_product_page = get_option('af_bid_auctions_product_page');
	?>
	<input type="checkbox" id="af_bid_auctions_product_page" name="af_bid_auctions_product_page" 
	<?php
	if (!empty($af_bid_auctions_product_page)) {
		echo 'checked';
	}
	?>
	>
	<p class="description"><?php echo esc_html__('Enable to show the auction badge on the auction product page', 'woocommerce'); ?></p>
	<?php
}

function af_bid_show_product_stock() {
	$af_bid_show_product_stock = get_option('af_bid_show_product_stock');
	?>
	<input type="checkbox" id="af_bid_show_product_stock" value="yes" name="af_bid_show_product_stock" 
	<?php
	if ('yes' === $af_bid_show_product_stock) {
		echo 'checked';
	}
	?>
	>
	<p><?php echo esc_html__('Enable to show the product quantity', 'woocommerce'); ?></p>
	<?php
}

function af_bid_reseve_product_price() {
	$af_bid_reseve_product_price = get_option('af_bid_reseve_product_price');
	?>
	<input type="checkbox" value="yes" id="af_bid_reseve_product_price" name="af_bid_reseve_product_price" 
	<?php
	if (!empty($af_bid_reseve_product_price)) {
		echo 'checked';
	}
	?>
	>
	<p class="description"><?php echo esc_html__('Enable to show a notice if the reserve price has been reached', 'woocommerce'); ?></p>
	<?php
}

function af_bid_auction_is_overtime() {
	$af_bid_auction_is_overtime = get_option('af_bid_auction_is_overtime');
	?>
	<input type="checkbox" id="af_bid_auction_is_overtime" name="af_bid_auction_is_overtime" 
	<?php
	if (!empty($af_bid_auction_is_overtime)) {
		echo 'checked';
	}
	?>
	>
	<p class="description"><?php echo esc_html__('Enable to show a notice if the auction is in overtime', 'woocommerce'); ?></p>
	<?php
}

function ct_automatic_bid_type() {
	$ct_automatic_bid_type = get_option('ct_automatic_bid_type');
	?>
	<select id="ct_automatic_bid_type" name="ct_automatic_bid_type" class="wc-enhanced-select">
		<option value="full_username_of_bidders" <?php selected($ct_automatic_bid_type, 'full_username_of_bidders'); ?>>
			<?php echo esc_html__('Full username of bidders', 'woocommerce'); ?>
		</option>
		<option value="only_first_letter_of_bidder" <?php selected($ct_automatic_bid_type, 'only_first_letter_of_bidder'); ?>>
			<?php echo esc_html__('Only first and last letter ( A****E )', 'woocommerce'); ?>
		</option>
	</select>
	<p class="description"><?php echo esc_html__('Choose whether to show the full username of bidders or only set the first and last letters. Note: In sealed auctions, the bids list will be hidden and this option will not be applied.', 'woocommerce'); ?></p>
	<?php
}
