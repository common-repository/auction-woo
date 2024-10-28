<?php

// Add SKU column to the product table
function add_sku_column_to_products( $columns ) {
	$columns['highest_bid'] = __('Highest Bid', 'your-text-domain');
	return $columns;
}
add_filter('manage_edit-product_columns', 'add_sku_column_to_products');

// Display SKU in the custom column
function display_sku_column_content( $column, $post_id ) {
	if ('highest_bid' === $column) {

		$current_user_bidding_detail = devsoul_get_bidding_products_details(array( $post_id ));
		// $highest_bid  = wp_list_pluk($current_user_bidding_detail,'','highest_bid');
		$highest_bids = wp_list_pluck($current_user_bidding_detail, 'highest_bid');

		echo esc_attr(max($highest_bids));

		ob_start();
		?>
		<div class="devsoul-current-user-detail">
			<button class="show-bidding-details button button-primary woocommerce-save-button">View Details</button>
			<div class="show-user-detail-in-popup" style="display:none;">

				<div class="popup-content">
					<button class="close-popup fa fa-close" style="float: right;"></button>
					<?php

					$current_user_bidding_detail = devsoul_get_bidding_products_details(array( $post_id ));

					devsoul_get_table_of_bidding_detail($current_user_bidding_detail);

					?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();

	}
}
add_action('manage_product_posts_custom_column', 'display_sku_column_content', 10, 2);


// Add Bidding Detail column to the Users table
function add_bidding_detail_column( $columns ) {
	$columns['bidding_detail'] = __('Bidding Detail', 'your-text-domain');
	return $columns;
}
add_filter('manage_users_columns', 'add_bidding_detail_column');

// Display button in the Bidding Detail column
function display_bidding_detail_column_content( $value, $column_name, $user_id ) {
	if ('bidding_detail' === $column_name) {
		ob_start();
		?>
		<div class="devsoul-current-user-detail">
			<button class="show-bidding-details button button-primary woocommerce-save-button"
				data-user-id="<?php echo esc_attr($user_id); ?>">View Details</button>
			<div class="show-user-detail-in-popup" style="display:none;">

				<div class="popup-content">
					<button class="close-popup fa fa-close" style="float: right;"></button>
					<?php
					$current_user_bidding_detail = devsoul_get_bidding_products_details(array(), $user_id);
					devsoul_get_table_of_bidding_detail($current_user_bidding_detail, $is_for_specific_user = true);
					?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
add_action('manage_users_custom_column', 'display_bidding_detail_column_content', 10, 3);


// Add custom columns to the custom post type 'offer_product'
function add_offer_product_columns( $columns ) {
	$columns['first_name'] = 'First Name';
	$columns['last_name'] = 'Last Name';
	$columns['phone_number'] = 'Phone Number';
	$columns['email_address'] = 'Email Address';
	$columns['product_name'] = 'Product Name';
	$columns['make_product'] = 'Make Product';

	return $columns;
}
add_filter('manage_edit-offer_product_columns', 'add_offer_product_columns');

// Populate custom columns with data
function populate_offer_product_columns( $column, $post_id ) {
	switch ($column) {
		case 'first_name':
			$first_name = get_post_meta($post_id, 'first_name', true);
			echo esc_html($first_name);
			break;
		case 'last_name':
			$last_name = get_post_meta($post_id, 'last_name', true);
			echo esc_html($last_name);
			break;
		case 'phone_number':
			$phone_number = get_post_meta($post_id, 'phone_number', true);
			echo esc_html($phone_number);
			break;
		case 'email_address':
			$email_address = get_post_meta($post_id, 'email_address', true);
			echo esc_html($email_address);
			break;
		case 'product_name':
			$product_name = get_the_title($post_id);
			echo esc_html($product_name);
			break;
		case 'make_product':
			?>
			<i class="devsoul-make-product button button-primary button-large">Make Product</i>
			<?php
			break;
	}
}
add_action('manage_offer_product_posts_custom_column', 'populate_offer_product_columns', 10, 2);