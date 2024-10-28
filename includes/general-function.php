<?php
function devsoul_get_all_bidding_products() {
	$city_bidding_args = get_posts(
		array(
			'post_type' => 'city_bid_for_woo',
			'post_status' => 'publish',
			'numberposts' => -1,
			'return' => 'ids',
			'fields' => 'ids',
			'meta_query' => array(
				array(
					'key' => 'af_bid_review_products',
					'compare' => 'EXISTS',
				),
			),
		)
	);
	$auction_products = get_posts(
		array(
			'post_type' => 'product',
			'numberposts' => -1,
			'post_status' => 'publish',
			'return' => 'ids',
			'fields' => 'ids',
			'meta_query' => array(
				array(
					'key' => 'dev_enabel_auction_product',
					'value' => 'yes',
					'compare' => '=',
				),
			),
		)
	);

	foreach ($city_bidding_args as $current_post_id) {
		$product_ids = (array) get_post_meta($current_post_id, 'af_bid_review_products', true);
		$auction_products = array_merge($auction_products, $product_ids);
		$devsoul_cat_ids = (array) get_post_meta($current_post_id, 'af_bid_category_review', true);

		$product_ids = get_posts(
			array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'return' => 'ids',
				'fields' => 'ids',
				'posts_per_page' => -1, // Fetch all posts
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => $devsoul_cat_ids,
						'operator' => 'IN',
					),
				),
			)
		);

		$auction_products = array_merge($auction_products, $product_ids);
	}
	return $auction_products;
}

function devsoul_get_bidding_products_details( $auction_products = array(), $specific_user_id = 0 ) {

	$auction_products = (array) $auction_products;

	$auction_products = !empty($auction_products) ? $auction_products : devsoul_get_all_bidding_products();
	$auction_products = array_unique($auction_products);
	$date_format = get_option('date_format');

	$bidding_products_details = array();

	foreach ($auction_products as $product_id) {
		$product = wc_get_product($product_id);

		if (!$product_id) {
			continue;
		}
		$devsoul_post_data = get_posts(
			array(
				'numberposts' => -1,
				'post_type' => 'bidding_for_user',
				'post_status' => 'publish',
				'post_parent' => $product->get_id(),
				'fields' => 'ids',
			)
		);

		$total_spend_amount = array( 0 );
		$curren_post_detail = array();
		if ($specific_user_id >= 1) {
			foreach ($devsoul_post_data as $rule_id) {
				$form_data = (array) get_post_meta($rule_id, 'bpg_formdata', true);
				$bidd_user_id = isset($form_data['current_user_id']) ? $form_data['current_user_id'] : 0;

				if ($bidd_user_id == $specific_user_id) {
					$total_spend_amount[ $rule_id ] = isset($form_data['ct_bid_price']) ? $form_data['ct_bid_price'] : 0;
					$curren_post_detail['your_bid'] = isset($form_data['ct_bid_price']) ? $form_data['ct_bid_price'] : 0;
					$curren_post_detail['current_user_id'] = isset($form_data['current_user_id']) ? $form_data['current_user_id'] : 0;

					break;
				}
			}

		} else {
			foreach ($devsoul_post_data as $rule_id) {

				$form_data = (array) get_post_meta($rule_id, 'bpg_formdata', true);
				$total_spend_amount[ $rule_id ] = isset($form_data['ct_bid_price']) ? $form_data['ct_bid_price'] : 0;
			}

		}


		$max_bid_rule_id = array_search(max($total_spend_amount), $total_spend_amount);
		$form_data = array_merge((array) get_post_meta($max_bid_rule_id, 'bpg_formdata', true), $curren_post_detail);

		$status = get_post_meta($max_bid_rule_id, 'ct_bidding_status', true) ? get_post_meta($max_bid_rule_id, 'ct_bidding_status', true) : 'Pending';


		$bidding_products_details[ $product_id ] = array_merge(
			$form_data,
			array(
				'highest_bid' => max($total_spend_amount),
				'status' => $status,
				'date' => get_the_date($date_format, $max_bid_rule_id),
				'product_id' => $product_id,
				'max_bid_rule_id' => $max_bid_rule_id,
			)
		);
	}
	return $bidding_products_details;
}

function devsoul_get_table_of_bidding_detail( $current_user_bidding_detail = array(), $is_for_specific_user = false ) {

	?>
	<div class="devsoul-table-detail">
		<?php

		if (!empty($current_user_bidding_detail)) {
			?>
			<i
				class="button button-primary woocommerce-save-button devsoul-biiding-detail-export-csv-btn"><?php echo esc_html__('Export CSV', 'auction-woo'); ?></i>
			<?php
		}

		if (!empty($current_user_bidding_detail) && $is_for_specific_user) {


			echo '<table class="bidding-data-detail-table wp-list-table widefat fixed striped table-view-list">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>Product ID</th>';
			echo '<th>Product Name</th>';
			echo '<th>Your Bid</th>';
			echo '<th>Highest Bid</th>';
			echo '<th>Status</th>';
			echo '<th>Date</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			foreach ($current_user_bidding_detail as $bidding) {

				$product_id = isset($bidding['product_id']) ? $bidding['product_id'] : 'N/A';
				$product_name = get_the_title($product_id);
				$your_bid = isset($bidding['ct_bid_price']) ? $bidding['ct_bid_price'] : 'N/A';
				$highest_bid = isset($bidding['highest_bid']) ? $bidding['highest_bid'] : 'N/A';
				$status = isset($bidding['status']) ? $bidding['status'] : 'N/A';
				$date = isset($bidding['date']) ? $bidding['date'] : 'N/A';

				echo '<tr>';
				echo '<td>' . esc_html($product_id) . '</td>';
				echo '<td>' . esc_html($product_name) . '</td>';
				echo '<td>' . esc_html($your_bid) . '</td>';
				echo '<td>' . esc_html($highest_bid) . '</td>';
				echo '<td>' . esc_html($status) . '</td>';
				echo '<td>' . esc_html($date) . '</td>';
				echo '</tr>';
			}

			echo '</tbody>';
			echo '</table>';
		} elseif (!empty($current_user_bidding_detail) && !$is_for_specific_user) {

			echo '<table class="bidding-data-detail-table wp-list-table widefat fixed striped table-view-list">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>Product ID</th>';
			echo '<th>Product Name</th>';
			echo '<th>Highest Bid</th>';
			echo '<th>Status</th>';
			echo '<th>Date</th>';
			echo '<th>Action</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			foreach ($current_user_bidding_detail as $bidding) {

				$product_id = isset($bidding['product_id']) ? $bidding['product_id'] : 'N/A';
				$product_name = get_the_title($product_id);
				$your_bid = isset($bidding['ct_bid_price']) ? $bidding['ct_bid_price'] : 'N/A';
				$highest_bid = isset($bidding['highest_bid']) ? $bidding['highest_bid'] : 'N/A';
				$status = isset($bidding['status']) ? $bidding['status'] : 'N/A';
				$date = isset($bidding['date']) ? $bidding['date'] : 'N/A';

				echo '<tr>';
				echo '<td>' . esc_html($product_id) . '</td>';
				echo '<td>' . esc_html($product_name) . '</td>';
				echo '<td>' . esc_html($highest_bid) . '</td>';
				echo '<td>' . esc_html($status) . '</td>';
				echo '<td>' . esc_html($date) . '</td>';
				echo '<td><a href="' . esc_url(admin_url('admin.php?page=bidding-settings&tab=dashboard&product_id=' . $product_id)) . '">View Detail Bidding</a></td>';
				echo '</tr>';
			}


			echo '</tbody>';
			echo '</table>';
		} else {
			echo '<p>No bidding details found.</p>';
		}

		?>
	</div>
	<?php
}