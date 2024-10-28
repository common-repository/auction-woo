<?php
add_settings_section(
 'devsoul_bfw_general_settings_fields',
 '', // Title to be displayed on the administration page.
 '', // Callback used to render the description of the section.
 'devsoul_bfw_general_settings_sections'
);
add_settings_field(
 'af_bid_auctions_shop_page',
 esc_html__('Show auctions on the shop page', ' devsoul_bfwcac_text_d'), // The label.
 'af_bid_auctions_shop_page',
 'devsoul_bfw_general_settings_sections', // The page on which this option will be displayed.
 'devsoul_bfw_general_settings_fields'
);
register_setting(
 'devsoul_bfw_general_settings_fields',
 'af_bid_auctions_shop_page'
);


add_settings_field(
 'af_bid_hide_after_fisrt_bid',
 esc_html__('Hide Buy Now button ', ' devsoul_bfwcac_text_d'), // The label.
 'af_bid_hide_after_fisrt_bid',
 'devsoul_bfw_general_settings_sections', // The page on which this option will be displayed.
 'devsoul_bfw_general_settings_fields'
);
register_setting(
 'devsoul_bfw_general_settings_fields',
 'af_bid_hide_after_fisrt_bid'
);


add_settings_field(
 'af_bid_approval',
 esc_html__(' Ask for bid approval ', ' devsoul_bfwcac_text_d'), // The label.
 'af_bid_approval',
 'devsoul_bfw_general_settings_sections', // The page on which this option will be displayed.
 'devsoul_bfw_general_settings_fields'
);
register_setting(
 'devsoul_bfw_general_settings_fields',
 'af_bid_approval'
);



function af_bid_show_higher_bider() {
	$af_bid_show_higher_bider = get_option('af_bid_show_higher_bider');
	?>
	<input type="checkbox" id="af_bid_show_higher_bider" name="af_bid_show_higher_bider" value="yes" 
	<?php
	if ('yes' == $af_bid_show_higher_bider) {
		echo 'checked';
	}
	?>
	>
	<p class="description">
		<?php echo esc_html__('Enable to show a modal to the highest bidder to suggest him to refresh the page.', 'woocommerce'); ?>
	</p>
	<?php
}

function af_bid_approval() {
	$af_bid_approval = get_option('af_bid_approval');
	?>
	<input type="checkbox" id="af_bid_approval" name="af_bid_approval" value="yes" 
	<?php
	if ('yes' == $af_bid_approval) {
		echo 'checked';
	}
	?>
	>
	<p class="description">
		<?php echo esc_html__('If enabled, bidders will see a modal window that asks them to confirm the bid before publishing it.', 'woocommerce'); ?>
	</p>

	<?php
}





function af_bid_hide_after_fisrt_bid() {
	$af_bid_hide_after_fisrt_bid = get_option('af_bid_hide_after_fisrt_bid');
	?>
	<input type="checkbox" id="af_bid_hide_after_fisrt_bid" value="yes" name="af_bid_hide_after_fisrt_bid" 
	<?php
	if (!empty($af_bid_hide_after_fisrt_bid)) {
		echo 'checked';
	}
	?>
	>
	<p class="description">
		<?php echo esc_html__('Enable to hide Buy Now button when a user places the first bid.', 'woocommerce'); ?>
	</p>


	<?php
}

function af_bid_auctions_shop_page() {

	$af_bid_auctions_shop_page = get_option('af_bid_auctions_shop_page');

	?>
	<input type="checkbox" value="yes" id="af_bid_auctions_shop_page" name="af_bid_auctions_shop_page" 
	<?php
	if (!empty($af_bid_auctions_shop_page)) {
		echo 'checked';
	}
	?>
	>

	<p class="description"><?php echo esc_html__('Enable to show auction products in the shop page.', 'woocommerce'); ?></p>

	<?php
}


add_settings_section(
 'devsoul_bfw_sold_product_setting',
 'Sold Product Setting', // Title to be displayed on the administration page.
 '', // Callback used to render the description of the section.
 'devsoul_bfw_general_settings_sections'
);
add_settings_field(
 'af_bid_sold_product_setting',
 esc_html__('Show Sold Badge On Product', ' devsoul_bfwcac_text_d'), // The label.
 function () {
		?>
	<input type="checkbox" value="yes" name="af_bid_sold_product_setting" <?php checked('yes', get_option('af_bid_sold_product_setting')); ?>>
		  <?php
	},
 'devsoul_bfw_general_settings_sections', // The page on which this option will be displayed.
 'devsoul_bfw_sold_product_setting'
);
register_setting(
 'devsoul_bfw_general_settings_fields',
 'af_bid_sold_product_setting'
);

add_settings_field(
 'af_bid_select_badge',
 esc_html__('Select Badge', ' devsoul_bfwcac_text_d'), // The label.
 function () {
		?>
	<i class="fa fa-upload devsoul-upload-badge"></i>
	<input type="hidden" value="<?php echo esc_attr(get_option('af_bid_select_badge')); ?>" name="af_bid_select_badge">
		  <?php
	},
 'devsoul_bfw_general_settings_sections', // The page on which this option will be displayed.
 'devsoul_bfw_sold_product_setting'
);
register_setting(
 'devsoul_bfw_general_settings_fields',
 'af_bid_select_badge'
);

add_settings_field(
 'af_bid_hide_sold_product_in_dashboard',
 esc_html__('Hide Sold Product From Shop Page', ' devsoul_bfwcac_text_d'), // The label.
 function () {
		?>
	<input type="checkbox" value="yes" name="af_bid_hide_sold_product_in_dashboard" <?php checked('yes', get_option('af_bid_hide_sold_product_in_dashboard')); ?>>
		  <?php
	},
 'devsoul_bfw_general_settings_sections', // The page on which this option will be displayed.
 'devsoul_bfw_sold_product_setting'
);
register_setting(
 'devsoul_bfw_general_settings_fields',
 'af_bid_hide_sold_product_in_dashboard'
);

add_settings_field(
 'af_bid_show_sold_product_in_dashboard',
 esc_html__('Show Sold Product Table on Dashboard', ' devsoul_bfwcac_text_d'), // The label.
 function () {
		?>
	<input type="checkbox" value="yes" name="af_bid_show_sold_product_in_dashboard" <?php checked('yes', get_option('af_bid_show_sold_product_in_dashboard')); ?>>
		  <?php
	},
 'devsoul_bfw_general_settings_sections', // The page on which this option will be displayed.
 'devsoul_bfw_sold_product_setting'
);
register_setting(
 'devsoul_bfw_general_settings_fields',
 'af_bid_show_sold_product_in_dashboard'
);



