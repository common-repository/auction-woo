<?php
if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('CT_B_I_D_Front')) {

	class CT_B_I_D_Front {
	


		public function __construct() {

			add_action('woocommerce_product_meta_start', array( $this, 'ct_show_bidding_product_page' ));
			add_filter('woocommerce_get_price_html', array( $this, 'custom_price_html' ), 10, 2);
			add_filter('woocommerce_is_purchasable', array( $this, 'ct_bid_hide_add_cart' ), 10, 1);
			add_action('wp_enqueue_scripts', array( $this, 'ct_bid_scripts' ));
			add_action('woocommerce_before_shop_loop_item_title', array( $this, 'add_custom_banner_to_product_image' ), 10, 1);
			add_action('woocommerce_single_product_summary', array( $this, 'ct_count_down_timer' ), 10);
			add_shortcode('devsoul_show_bidding_all_products', array( $this, 'devsoul_show_bidding_all_products' ));
			add_shortcode('devsoul_show_product_offer_form', array( $this, 'devsoul_show_product_offer_form' ));


			// adding menu items in my account.

			add_filter('query_vars', array( $this, 'add_order_item_status_query_vars' ), 0);
			add_action('woocommerce_account_bidding-detail_endpoint', array( $this, 'display_order_item_status_endpoint_content' ));
			add_filter('woocommerce_account_menu_items', array( $this, 'add_order_item_status_link_my_account' ));
			$this->add_order_item_status_endpoint();
		}
		public function devsoul_show_product_offer_form() {
			ob_start();
			?>
			<style>
				.product-offer-form-container {
					max-width: 800px;
					margin: 0 auto;
					padding: 20px;
					background-color: #f9f9f9;
					border: 1px solid #ddd;
					border-radius: 10px;
				}

				.product-offer-form-container h2 {
					text-align: center;
					margin-bottom: 20px;
				}

				.product-offer-form-container table {
					width: 100%;
					border-collapse: collapse;
				}

				.product-offer-form-container th,
				.product-offer-form-container td {
					padding: 10px;
					vertical-align: top;
				}

				.product-offer-form-container label {
					display: block;
					margin-bottom: 5px;
					font-weight: bold;
				}

				.product-offer-form-container input[type="text"],
				.product-offer-form-container input[type="email"],
				.product-offer-form-container input[type="tel"],
				.product-offer-form-container textarea,
				.product-offer-form-container select,
				.product-offer-form-container input[type="file"] {
					width: 100%;
					padding: 8px;
					border: 1px solid #ccc;
					border-radius: 5px;
					box-sizing: border-box;
				}

				.product-offer-form-container input[type="submit"] {
					display: block;
					width: 100%;
					padding: 10px;
					background-color: #0073aa;
					color: white;
					border: none;
					border-radius: 5px;
					cursor: pointer;
				}

				.product-offer-form-container input[type="submit"]:hover {
					background-color: #005177;
				}

				.product-offer-form-container .g-recaptcha {
					margin: 20px 0;
				}
			</style>

			<div class="product-offer-form-container">
				<h2>Sell your product with us</h2>

				<form method="post" enctype="multipart/form-data">
					<table>
						<?php if (get_option('devsoul_bfw_first_name')) : ?>
							<tr>
								<td><label for="first_name">First Name *</label></td>
								<td><input type="text" id="first_name" name="first_name" required></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_last_name')) : ?>
							<tr>
								<td><label for="last_name">Last Name *</label></td>
								<td><input type="text" id="last_name" name="last_name" required></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_email_address')) : ?>
							<tr>
								<td><label for="email_address">Email Address *</label></td>
								<td><input type="email" id="email_address" name="email_address" required></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_phone_number')) : ?>
							<tr>
								<td><label for="phone_number">Phone Number *</label></td>
								<td><input type="tel" id="phone_number" name="phone_number" required></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_product_year')) : ?>
							<tr>
								<td><label for="product_year">What year is your product?</label></td>
								<td><input type="text" id="product_year" name="product_year"></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_product_make')) : ?>
							<tr>
								<td><label for="product_make">What make is this product? *</label></td>
								<td><input type="text" id="product_make" name="product_make" required></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_product_model')) : ?>
							<tr>
								<td><label for="product_model">What model is this product? *</label></td>
								<td><input type="text" id="product_model" name="product_model" required></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_product_type')) : ?>
							<tr>
								<td><label for="product_type">What type is this product?</label></td>
								<td><input type="text" id="product_type" name="product_type"></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_product_state')) : ?>
							<tr>
								<td><label for="product_state">What state is the product currently located in? *</label></td>
								<td><input type="text" id="product_state" name="product_state" required></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_product_city')) : ?>
							<tr>
								<td><label for="product_city">What city is the product currently located in? *</label></td>
								<td><input type="text" id="product_city" name="product_city" required></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_dealer')) : ?>
							<tr>
								<td><label for="dealer">Are you a dealer?</label></td>
								<td>
									<select id="dealer" name="dealer" required>
										<option value="">Choose</option>
										<option value="yes">Yes</option>
										<option value="no">No</option>
									</select>
								</td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_product_videos')) : ?>
							<tr>
								<td><label for="product_videos">Please provide any links to videos (YouTube or Vimeo) here:</label></td>
								<td><textarea id="product_videos" name="product_videos"></textarea></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_product_details')) : ?>
							<tr>
								<td><label for="product_details">Product Details: *</label></td>
								<td><textarea id="product_details" name="product_details" required></textarea></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_product_photos')) : ?>
							<tr>
								<td><label for="product_photos">Please upload photos of your product *</label></td>
								<td><input type="file" id="product_photos" name="product_photos[]" multiple required></td>
							</tr>
						<?php endif; ?>
						<?php if (get_option('devsoul_bfw_accept_terms')) : ?>
							<tr>
								<td colspan="2">
									<label>
										<input type="checkbox" name="accept_terms" required> If submittal accepted, CWTW will edit if
										necessary to keep all listings consistent and to current CWTW standard.
									</label>
								</td>
							</tr>
						<?php endif; ?>
						<tr>
							<td colspan="2">
								<div class="g-recaptcha" data-sitekey="YOUR_RECAPTCHA_SITE_KEY"></div>
							</td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" value="Submit" class="ct_bid_offer_product"></td>
						</tr>
					</table>
				</form>

			</div>
			<?php
			return ob_get_clean();
		}






		public function add_custom_banner_to_product_image( $product ) {

			global $product;
			$ct_rule_id = $this->ct_b_i_d_is_this_product_aplicable($product);

			if ($ct_rule_id && !empty($ct_rule_id)) {



				if ($product->get_id() == 22) {

					echo '<div class="custom-banner">Your Banner Text</div>';

				}

			}
		}
		public function ct_count_down_timer() {

			global $product;
			$ct_rule_id = $this->ct_b_i_d_is_this_product_aplicable($product);

			if ($ct_rule_id && !empty($ct_rule_id)) {
				$ct_bid_start_time = get_post_meta($ct_rule_id, 'ct_bid_start_time', true);
				$ct_bid_start_date = get_post_meta($ct_rule_id, 'ct_bid_start_date', true);
				$ct_bid_end_date = get_post_meta($ct_rule_id, 'ct_bid_end_date', true);
				$ct_bid_end_time = get_post_meta($ct_rule_id, 'ct_bid_end_time', true);

				?>
				<div id="countdown">
					<div id='devsoul-counter-tiles'></div>
					<div class="devsoul-counter-labels">
						<li>Days</li>
						<li>Hours</li>
						<li>Mins</li>
						<li>Secs</li>
					</div>
				</div>
				<style>
					/*body {
																																											font: normal 13px/20px Arial, Helvetica, sans-serif;
																																											word-wrap: break-word;
																																											color: #eee;
																																										}*/

					#countdown {
						/*					width: 426px;*/
						height: 150px;
						text-align: center;
						background: #222;
						background-image: -webkit-linear-gradient(top, #222, #333, #333, #222);
						background-image: -moz-linear-gradient(top, #222, #333, #333, #222);
						background-image: -ms-linear-gradient(top, #222, #333, #333, #222);
						background-image: -o-linear-gradient(top, #222, #333, #333, #222);
						border: 1px solid #111;
						border-radius: 5px;
						box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.6);
						margin: 20px 0;
						padding: 24px 0;
					}

					#countdown:before {
						content: "";
						width: 8px;
						height: 65px;
						background: #444;
						background-image: -webkit-linear-gradient(top, #555, #444, #444, #555);
						background-image: -moz-linear-gradient(top, #555, #444, #444, #555);
						background-image: -ms-linear-gradient(top, #555, #444, #444, #555);
						background-image: -o-linear-gradient(top, #555, #444, #444, #555);
						border: 1px solid #111;
						border-top-left-radius: 6px;
						border-bottom-left-radius: 6px;
						display: block;
						position: absolute;
						top: 48px;
						left: -10px;
					}

					#countdown:after {
						content: "";
						width: 8px;
						height: 65px;
						background: #444;
						background-image: -webkit-linear-gradient(top, #555, #444, #444, #555);
						background-image: -moz-linear-gradient(top, #555, #444, #444, #555);
						background-image: -ms-linear-gradient(top, #555, #444, #444, #555);
						background-image: -o-linear-gradient(top, #555, #444, #444, #555);
						border: 1px solid #111;
						border-top-right-radius: 6px;
						border-bottom-right-radius: 6px;
						display: block;
						position: absolute;
						top: 48px;
						right: -10px;
					}

					#devsoul-counter-tiles {
						display: inline-block;
						position: relative;
						z-index: 1;
					}

					#devsoul-counter-tiles>span {
						width: 92px;
						max-width: 92px;
						font: bold 48px 'Droid Sans', Arial, sans-serif;
						text-align: center;
						color: #111;
						background-color: #ddd;
						background-image: -webkit-linear-gradient(top, #bbb, #eee);
						background-image: -moz-linear-gradient(top, #bbb, #eee);
						background-image: -ms-linear-gradient(top, #bbb, #eee);
						background-image: -o-linear-gradient(top, #bbb, #eee);
						border-top: 1px solid #fff;
						border-radius: 3px;
						box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.7);
						margin: 0 7px;
						padding: 18px 0;
						display: inline-block;
						position: relative;
					}

					#devsoul-counter-tiles>span:before {
						content: "";
						width: 100%;
						height: 13px;
						background: #111;
						display: block;
						padding: 0 3px;
						position: absolute;
						top: 41%;
						left: -3px;
						z-index: -1;
					}

					#devsoul-counter-tiles>span:after {
						content: "";
						width: 100%;
						height: 1px;
						background: #eee;
						border-top: 1px solid #333;
						display: block;
						position: absolute;
						top: 48%;
						left: 0;
					}

					.devsoul-counter-labels {
						width: 100%;
						height: 25px;
						text-align: center;
						bottom: 8px;
						padding-top: 5px;
					}

					.devsoul-counter-labels li {
						width: 102px;
						font: bold 15px 'Droid Sans', Arial, sans-serif;
						color: #f47321;
						text-shadow: 1px 1px 0px #000;
						text-align: center;
						text-transform: uppercase;
						display: inline-block;
					}
				</style>
				<script>
					// Convert PHP timestamps to JavaScript timestamps
					var start_date = Date.parse("<?php echo esc_js(gmdate('Y-m-d H:i:s', strtotime($ct_bid_start_date . ' ' . $ct_bid_start_time))); ?>");
					var end_date = Date.parse("<?php echo esc_js(gmdate('Y-m-d H:i:s', strtotime($ct_bid_end_date . ' ' . $ct_bid_end_time))); ?>");

					// Determine which timestamp to use for the countdown
					var current_date = new Date().getTime();
					var target_date = current_date < start_date ? start_date : end_date;

					var days, hours, minutes, seconds; // variables for time units

					var countdown = document.getElementById("devsoul-counter-tiles"); // get tag element

					getCountdown();

					setInterval(function () { getCountdown(); }, 1000);

					function getCountdown() {
						// find the amount of "seconds" between now and target
						var current_date = new Date().getTime();
						var seconds_left = (target_date - current_date) / 1000;

						// Check if the countdown has reached the end time
						if (seconds_left <= 0) {
							countdown.innerHTML = "<span>00</span><span>00</span><span>00</span><span>00</span>";
							return;
						}

						days = pad(parseInt(seconds_left / 86400));
						seconds_left = seconds_left % 86400;

						hours = pad(parseInt(seconds_left / 3600));
						seconds_left = seconds_left % 3600;

						minutes = pad(parseInt(seconds_left / 60));
						seconds = pad(parseInt(seconds_left % 60));

						// format countdown string + set tag value
						countdown.innerHTML = "<span>" + days + "</span><span>" + hours + "</span><span>" + minutes + "</span><span>" + seconds + "</span>";
					}

					function pad(n) {
						return (n < 10 ? '0' : '') + n;
					}
				</script>

				<?php
			}
		}








		public function ct_bid_scripts() {
			$arg = get_posts(
				array(
					'numberposts' => -1,
					'post_type' => 'city_bid_for_woo',
					'post_status' => 'publish',
					'fields' => 'ids',
				)
			);


			wp_enqueue_script('bid_front_js', BID_URL . '/assest/js/frontbid.js', array( 'jquery' ), '1.0.0', true);
			wp_enqueue_style('bid_front', BID_URL . '/assest/css/bid_styling.css', array(), '1.1');



			$current_user = wp_get_current_user();
			$af_bid_hide_after_fisrt_bid = get_option('af_bid_hide_after_fisrt_bid');


			$bid_ajax = array(
				'admin_url' => admin_url('admin-ajax.php'),
				'current_user_name' => $current_user->display_name,
				'current_user_email' => $current_user->user_email,
				'af_bid_hide_after_fisrt_bid' => $af_bid_hide_after_fisrt_bid,
				'nonce' => wp_create_nonce('ct_dfw_nonce'),
			);
			wp_localize_script('bid_front_js', 'php_vars_bid', $bid_ajax);
		}
		public function ct_bid_hide_add_cart( $is_purchasable ) {
			global $product;
			$bid_rule_active = $this->ct_b_i_d_is_this_product_aplicable($product);
			if ($bid_rule_active && !empty($bid_rule_active)) {
				return false;
			}
			return $is_purchasable;
		}
		public function ct_show_bidding_product_page() {
			global $product;
			$is_any_rule_active = $this->ct_b_i_d_is_this_product_aplicable($product);
			$af_bid_show_product_stock = get_option('af_bid_show_product_stock');

			$ct_bid_start_date = get_post_meta($is_any_rule_active, 'ct_bid_start_date', true);
			$ct_bid_start_time = get_post_meta($is_any_rule_active, 'ct_bid_start_time', true);


			if ($is_any_rule_active && !empty($is_any_rule_active)) {
				?>
				<form>

					<div>
						<input type="number" min="0" name="ct_bid_price" id="ct_bid_price"></input>
						<input type="button" name="ct_bid_now_button" class="ct_bidding_button"
							value="<?php esc_html_e('Bid Now', 'auction-woo'); ?>"></input>
						<input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>"></input>
					</div>
				</form>
				<br>
				<?php
				$ct_automatic_bid_type = get_option('ct_automatic_bid_type');
				?>
				<input type="button" name="ct_bid_show_bidder" class="ct_bid_show_bidder"
					value="<?php echo esc_html_e('Show all Bidder', 'auction-woo'); ?>">
				<div class="ct_bidder_popup" style="display: none;">
					<div class="popup_overlay"></div>
					<div class="popup_content">
						<button class="close_popup">X</button>
						<h2><?php echo esc_html_e('Bidders List', 'auction-woo'); ?></h2>
						<div class="bidders_list">
							<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
								<tr style="background-color: #f2f2f2;">
									<th style="border: 1px solid #ddd; padding: 8px; text-align: left; color:#000000;">User Name</th>
									<th style="border: 1px solid #ddd; padding: 8px; text-align: left; color:#000000;">Bid Price</th>
								</tr>
								<?php
								$args = array(
									'numberposts' => -1,
									'post_type' => 'bidding_for_user',
									'post_status' => 'publish',
									'fields' => 'ids',
									'post_parent' => $product->get_id(),
								);
								$bidders = get_posts($args);
								foreach ($bidders as $bidder_id) {
									$form = (array) get_post_meta($bidder_id, 'bpg_formdata', true);
									$bid_price = isset($form['ct_bid_price']) ? $form['ct_bid_price'] : 'N/A';
									$user_id = isset($form['current_user_id']) ? $form['current_user_id'] : 'N/A';
									$user_name = 'N/A' !== $user_id ? get_user_by('id', $user_id)->display_name : 'Unknown User';
									if ('only_first_letter_of_bidder' == $ct_automatic_bid_type && 'Unknown User' !== $user_name) {
										$first_letter = substr($user_name, 0, 1);
										$last_letter = substr($user_name, -1);
										$user_name = $first_letter . ' ' . $last_letter;
									}

									echo '<tr>';
									echo '<td style="border: 1px solid #ddd; padding: 8px; color:#000000;">' . esc_html($user_name) . '</td>';
									echo '<td style="border: 1px solid #ddd; padding: 8px; color:#000000;">' . esc_html($bid_price) . '</td>';
									echo '</tr>';
								}
								?>
							</table>
						</div>
					</div>
				</div>
				<?php
			}
		}


		public function custom_price_html( $price, $product ) {
			$bid_rule_id = $this->ct_b_i_d_is_this_product_aplicable($product);

			if (!empty($bid_rule_id)) {
				$ct_bid_product_change_price = get_post_meta($bid_rule_id, 'ct_start_bidding_price', true);
				$ct_reserve_price = get_post_meta($bid_rule_id, 'ct_reserve_price', true);
				$currency_symbol = get_woocommerce_currency_symbol();
				$price = $currency_symbol . $ct_bid_product_change_price;
				$price .= ' <strong>(Reserve Price: ' . $currency_symbol . $ct_reserve_price . ')</strong>';
			}

			return $price;
		}

		public function ct_b_i_d_is_this_product_aplicable( $product ) {
			if (!$product) {
				return;
			}

			$arg = get_posts(
				array(
					'numberposts' => -1,
					'post_type' => 'city_bid_for_woo',
					'post_status' => 'publish',
					'fields' => 'ids',
				)
			);
			$flags = false;

			foreach ($arg as $rule_id) {

				$roles = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';

				$user_role = (array) get_post_meta($rule_id, 'ct_bid_user_role', true);
				$ct_bid_select_prod = (array) get_post_meta($rule_id, 'af_bid_review_products', true);
				$af_bid_category = (array) get_post_meta($rule_id, 'af_bid_category_review', true);

				if (!empty($user_role) && !in_array($roles, $user_role)) {
					continue;
				}

				if (!empty($ct_bid_select_prod) && !empty($af_bid_category)) {
					$flags = true;
				}
				if (!empty($ct_bid_select_prod) && null !== $product) {
					if (is_object($product) && method_exists($product, 'get_id')) {
						if (in_array($product->get_id(), $ct_bid_select_prod)) {
							$flags = true;
						}
					}
				}

				if (!empty($af_bid_category) && has_term($af_bid_category, 'product_cat', $product->get_id())) {
					$flags = true;
				}

				if ($flags) {
					return $rule_id;
				}
			}
		}
		public function devsoul_show_bidding_all_products() {
			ob_start();
			$all_bidding_products = devsoul_get_all_bidding_products();
			?>
			<table class="wp-list-table widefat fixed striped table-view-list">
				<thead>
					<th>Product ID</th>
					<th>Product Name</th>
					<th>Product SKU</th>
					<th>Short Description</th>
					<th>Action</th>
				</thead>
				<tbody>
					<?php
					foreach ($all_bidding_products as $product_id) {

						$current_product = wc_get_product($product_id);

						if ($current_product) {
							?>
							<tr>
								<td><?php echo esc_html($current_product->get_id()); ?></td>
								<td><?php echo esc_html($current_product->get_title()); ?></td>
								<td><?php echo esc_html($current_product->get_sku()); ?></td>
								<td><?php echo esc_html(wc_trim_string($current_product->get_short_description(), 50)); ?></td>
								<td><a href="<?php echo esc_url($current_product->get_permalink()); ?>">View</a></td>
							</tr>

							<?php
						}
					}
					?>
				</tbody>
			</table>
			<?php

			return ob_get_clean();
		}



		public function add_order_item_status_endpoint() {
			add_rewrite_endpoint('bidding-detail', EP_ROOT | EP_PAGES);
		}

		public function add_order_item_status_query_vars( $vars ) {
			$vars[] = 'bidding-detail';
			return $vars;
		}

		public function add_order_item_status_link_my_account( $items ) {


			$new_item = array( 'bidding-detail' => __('Bidding Detail', 'text-domain') );

			$items = array_slice($items, 0, 1, true) + $new_item + array_slice($items, 1, null, true);

			return $items;
		}
		public function display_order_item_status_endpoint_content() {
			$current_user_bidding_detail = devsoul_get_bidding_products_details(array(), get_current_user_id());

			devsoul_get_table_of_bidding_detail($current_user_bidding_detail, $is_for_specific_user = true);
		}
	}

	new CT_B_I_D_Front();
}
