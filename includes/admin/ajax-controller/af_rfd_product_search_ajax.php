<?php


add_action('wp_ajax_af_rfd_prod_search', 'af_rfd_prod_search');
add_action('wp_ajax_category_search', 'category_search');
add_action('wp_ajax_ct_dashboard_user_role', 'ct_dashboard_user_role');

function af_rfd_prod_search() {

	$nonce = isset($_POST['nonce']) && '' !== $_POST['nonce'] ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;
	if (!wp_verify_nonce($nonce, 'ct_dfw_nonce')) {
		die('Failed ajax security check!');
	}

	if (isset($_POST['q'])) {

		$pro = sanitize_text_field(wp_unslash($_POST['q']));
	} else {
		$pro = '';
	}

	$data_array = array();
	$args = array(
		'post_type' => array( 'product' ),
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'fields' => 'ids',
		's' => $pro,
	);

	$pros = get_posts($args);


	if (!empty($pros)) {
		foreach ($pros as $proo_ID) {
			$product_detail = wc_get_product($proo_ID);
			$title = ( mb_strlen($product_detail->get_name()) > 50 ) ? mb_substr($product_detail->get_name(), 0, 49) . '...' : $product_detail->get_name();
			$data_array[] = array( $proo_ID, $title ); // array( Post ID, Post Title ).
		}
	}
	echo wp_json_encode($data_array);
	die();
}

function category_search() {
	$nonce = isset($_POST['nonce']) && '' !== $_POST['nonce'] ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;

	if (isset($_POST['q']) && '' !== $_POST['q']) {
		if (!wp_verify_nonce($nonce, 'ct_dfw_nonce')) {
			die('Failed ajax security check!');
		}
		$pro = sanitize_text_field(wp_unslash($_POST['q']));
	} else {
		$pro = '';
	}
	$data_array = array();
	$orderby = 'name';
	$order = 'asc';
	$hide_empty = false;
	$cat_args = array(
		'taxonomy' => 'product_cat',
		'orderby' => $orderby,
		'order' => $order,
		'hide_empty' => $hide_empty,
		'name__like' => $pro,
	);

	$product_categories = get_terms($cat_args);

	if (!empty($product_categories)) {
		foreach ($product_categories as $proo) {
			$pro_front_post = ( mb_strlen($proo->name) > 50 ) ? mb_substr($proo->name, 0, 49) . '...' : $proo->name;
			$data_array[] = array( $proo->term_id, $pro_front_post );
		}
	}


	if (!empty($product_categories)) {
		foreach ($product_categories as $proo) {

			$pro_front_post = ( mb_strlen($proo->name) > 50 ) ? mb_substr($proo->name, 0, 49) . '...' : $proo->name;
			$data_array[] = array( $proo->term_id, $pro_front_post );
		}
	}


	echo wp_json_encode($data_array);
	die();
}

add_action('wp_ajax_ct_bidding_user', 'ct_bidding_user');
add_action('wp_ajax_nopriv_ct_bidding_user', 'ct_bidding_user');
add_action('wp_ajax_ct_email_data_user', 'ct_email_data_user');
add_action('wp_ajax_ct_bidding_offer_product', 'ct_bidding_offer_product');

add_action('wp_ajax_ct_disapprove_email', 'ct_disapprove_email');
function ct_disapprove_email() {
	$nonce = isset($_POST['nonce']) && '' !== $_POST['nonce'] ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;
	if (!wp_verify_nonce($nonce, 'ct_dfw_nonce')) {
		die('Failed ajax security check!');
	}
	if (isset($_POST['type']) && isset($_POST['current_post_id'])) {
		$type = sanitize_text_field($_POST['type']);
		$current_post_id = sanitize_text_field($_POST['current_post_id']);
		update_post_meta($current_post_id, 'ct_bidding_status', $type);
		WC()->mailer()->emails['CT_B_I_D_Lose_Auction_Email']->trigger($current_post_id);

	}
}

function ct_email_data_user() {
	$nonce = isset($_POST['nonce']) && '' !== $_POST['nonce'] ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;
	if (!wp_verify_nonce($nonce, 'ct_dfw_nonce')) {
		die('Failed ajax security check!');
	}


	if (isset($_POST['type']) && isset($_POST['current_post_id'])) {



		$type = sanitize_text_field($_POST['type']);
		$current_post_id = sanitize_text_field($_POST['current_post_id']);
		update_post_meta($current_post_id, 'ct_bidding_status', $type);
		WC()->mailer()->emails['CT_B_I_D_Auction_Win']->trigger($current_post_id);

	}
}
function ct_bidding_offer_product() {
	$nonce = isset($_POST['nonce']) && '' !== $_POST['nonce'] ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;
	if (!wp_verify_nonce($nonce, 'ct_dfw_nonce')) {
		die('Failed ajax security check!');
	}

	if (isset($_POST['form_data'])) {
		parse_str(sanitize_text_field($_POST['form_data']), $form_data);
		$post_data = array(
			'post_title' => sanitize_text_field($form_data['product_make'] . ' ' . $form_data['product_model']),
			'post_content' => sanitize_textarea_field($form_data['product_details']),
			'post_status' => 'publish',
			'post_type' => 'offer_product',
		);

		$post_id = wp_insert_post($post_data);


		if ($post_id && !is_wp_error($post_id)) {
			foreach ($form_data as $key => $value) {
				if ( 'product_details' !== $key ) {
					update_post_meta($post_id, $key, sanitize_text_field($value));
				}
			}

			wc_add_notice('Congratulations! Your form submit successfully', 'notice');
		} else {
			wc_add_notice('Form  not submit successfully', 'error');

		}
	} else {
		echo 'No form data received.';
	}

	wp_die();
}

function ct_bidding_user() {
	$nonce = isset($_POST['nonce']) && '' !== $_POST['nonce'] ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;
	if (!wp_verify_nonce($nonce, 'ct_dfw_nonce')) {
		die('Failed ajax security check!');
	}

	if (isset($_POST['form_data'])) {
		parse_str(sanitize_text_field($_POST['form_data']), $form_data);

		

		$current_user = wp_get_current_user();
		$form_data['current_user_id'] = get_current_user_id();
		$ct_bid_price = $form_data['ct_bid_price'] ? $form_data['ct_bid_price'] : 0;

		$arg = get_posts(
		 array(
			 'numberposts' => -1,
			 'post_type' => 'city_bid_for_woo',
			 'post_status' => 'publish',
			 'fields' => 'ids',
		 )
		);

		foreach ($arg as $rule_id) {
			$ct_bid_reserve_price = get_post_meta($rule_id, 'ct_reserve_price', true);
		}

		$af_bid_reseve_product_price = get_option('af_bid_reseve_product_price');
		if ($ct_bid_reserve_price < $ct_bid_price) {
			wc_add_notice('Sorry, your bid exceeded the reserve price limit', 'error');
		} else {

			$devsoul_post_data = get_posts(
			 array(
				 'numberposts' => -1,
				 'post_type' => 'bidding_for_user',
				 'post_status' => 'publish',
				 'post_parent' => isset($form_data['product_id']) ? $form_data['product_id'] : 0,
				 'fields' => 'ids',
			 )
			);

			foreach ($devsoul_post_data as $rule_id) {

				$form_data = (array) get_post_meta($rule_id, 'bpg_formdata', true);
				$bidd_user_id = isset($form_data['current_user_id']) ? $form_data['current_user_id'] : 0;

				if (get_current_user_id() >= 1 && get_current_user_id() == $bidd_user_id) {
					wp_delete_post($rule_id);
				}

			}

			$new_post_args = array(
				'post_title' => $current_user->display_name,
				'post_type' => 'bidding_for_user',
				'post_status' => 'publish',
				'post_parent' => isset($form_data['product_id']) ? $form_data['product_id'] : 0,
			);

			$new_post_id = wp_insert_post($new_post_args);
			update_post_meta($new_post_id, 'bpg_formdata', $form_data);

			if ($new_post_id) {
				wc_add_notice('Congratulations! Your bid placed successfully', 'notice');
			} else {
				wc_add_notice('Bid was placed successfully', 'error');
			}

		}
		wp_send_json(array( 'reload' => true ));

	}
	wp_die();
}

function ct_dashboard_user_role() {
	$nonce = isset($_POST['nonce']) && '' !== $_POST['nonce'] ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;
	if (!wp_verify_nonce($nonce, 'ct_dfw_nonce')) {
		die('Failed ajax security check!');
	}
	$search_query = isset($_POST['q']) ? sanitize_text_field(wp_unslash($_POST['q'])) : '';

	$users = get_users(
	 array(
		 'search' => '*' . $search_query . '*',
		 'search_columns' => array( 'user_login', 'user_nicename', 'user_email', 'display_name' ),
		 'number' => 10,
	 )
	);

	$user_data = array();
	foreach ($users as $user) {
		$user_data[] = array(
			'ID' => $user->ID,
			'display_name' => $user->display_name,
		);
	}

	wp_send_json($user_data);
	die();
}
