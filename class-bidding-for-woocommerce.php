<?php
/**
 * Plugin Name:       Cloud Technologies Auction For WooCommerce
 * Plugin URI:        https://cloudtechnologies.store/Invoice-payment-method-and-invoice-pdf
 * Description:       Enable your customers to participate in auctions for your products and secure them at the best possible price..
 * Version:           1.0.1
 * Author:            Cloud Technologies
 * Developed By:      Cloud Technologies
 * Author URI:        https://cloudtechnologies.store/
 * Support:           https://cloudtechnologies.store/
 * Domain Path:       /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       auction-woo
 * WC requires at least: 3.0.9
 * WC tested up to:    9.*.*
 */

if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('Bidding_For_Woocommerce')) {

	class Bidding_For_Woocommerce {
	
		public function __construct() {
			$this->devsoul_bpg_global_constents_vars();
			add_action('init', array( $this, 'devsoul_bafw_include_file' ));
			add_action('plugins_loaded', array( $this, ' devsoul_bafw_init ' ));
			add_action('before_woocommerce_init', array( $this, 'devsoul_bafw_hops_compatibility' ));
			add_action('wp_loaded', array( $this, 'bidding_domain' ));
		}

		public function devsoul_bafw_hops_compatibility() {
			if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
			}
		}

		
		public function devsoul_bafw_init() {

			// Check the installation of WooCommerce module if it is not a multi site.
			if (!is_multisite() && !in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {

				add_action('admin_notices', array( $this, 'devsoul_bafw_check_wocommerce' ));
			}
		}

		
		public function devsoul_bafw_check_wocommerce() {

			// Deactivate the plugin.
			deactivate_plugins(__FILE__);

			?>
			<div id="message" class="error">
				<p>
					<strong>
						<?php echo esc_html__('Cloud Technologies Auction For WooCommerce plugin is inactive. WooCommerce plugin must be active in order to activate it.', 'auction-woo'); ?>
					</strong>
				</p>
			</div>
			<?php
		}

		/**
		 * Includes Files.
		 *
		 * @since 1.0.0
		 */
		public function devsoul_bafw_include_file() {

			if (defined('WC_PLUGIN_FILE')) {



				add_filter('woocommerce_email_classes', array( $this, 'af_bid_email_body_share' ), 90, 1);

				add_filter('cron_schedules', array( $this, 'ct_bid_cron_schedules' ), 100, 2);

				include BID_PLUGIN_DIR . 'includes/general-function.php';

				include BID_PLUGIN_DIR . 'includes/admin/ajax-controller/af_rfd_product_search_ajax.php';

				if (is_admin()) {
					include BID_PLUGIN_DIR . 'includes/admin/class_ct_b_i_d_admin.php';
				} else {

					include BID_PLUGIN_DIR . 'includes/front/class_ct_b_i_d_front.php';
				}

				$labels = array(
					'name' => esc_html__('BId User For WooCommerce', 'auction-woo'),
					'singular_name' => esc_html__('BId User For WooCommerce', 'auction-woo'),
					'edit_item' => esc_html__('Edit BId User For WooCommerce ', 'auction-woo'),
					'new_item' => esc_html__('BId User For WooCommerce', 'auction-woo'),
					'view_item' => esc_html__('View BId User For WooCommerce Cart', 'auction-woo'),
					'search_items' => esc_html__('Search BId User For WooCommerce', 'auction-woo'),
					'not_found' => esc_html__('No BId User For WooCommerce found', 'auction-woo'),
					'not_found_in_trash' => esc_html__('No bestprice found in trash', 'auction-woo'),
					'menu_name' => esc_html__('Bidding for Woo', 'auction-woo'),
					'item_published' => esc_html__('BId User For WooCommerce published', 'auction-woo'),
					'item_updated' => esc_html__('BId User For WooCommerce updated', 'auction-woo'),
				);
				$supports = array(
					'title',
				);
				$options = array(
					'supports' => $supports,
					'labels' => $labels,
					'public' => true,
					'publicly_querable' => false,
					'query_var' => true,
					'capability_type' => 'post',
					'can_export' => true,
					'show_ui' => true,
					'show_in_admin_bar' => false,
					'exclude_from_search' => true,
					'show_in_menu' => 'woocommerce',
					'has_archive' => true,
					'rewrite' => array(
						'slug',
						'auction-woo',
						'with_front' => false,
					),
					'show_in_rest' => true,
				);
				register_post_type('bidding_for_user', $options);


				$labels = array(
					'name' => esc_html__('Bidding for WooCommerce', 'auction-woo'),
					'singular_name' => esc_html__('Bidding for WooCommerce', 'auction-woo'),
					'edit_item' => esc_html__('Edit Bidding for WooCommerce ', 'auction-woo'),
					'new_item' => esc_html__('Bidding for WooCommerce', 'auction-woo'),
					'view_item' => esc_html__('View Bidding for WooCommerce Cart', 'auction-woo'),
					'search_items' => esc_html__('Search Bidding for WooCommerce', 'auction-woo'),
					'not_found' => esc_html__('No Bidding for WooCommerce found', 'auction-woo'),
					'not_found_in_trash' => esc_html__('No bestprice found in trash', 'auction-woo'),
					'menu_name' => esc_html__('Bidding for Woo', 'auction-woo'),
					'item_published' => esc_html__('Bidding for WooCommerce published', 'auction-woo'),
					'item_updated' => esc_html__('Bidding for WooCommerce updated', 'auction-woo'),
				);
				$supports = array(
					'title',
					'page-attributes',
				);
				$options = array(
					'supports' => $supports,
					'labels' => $labels,
					'public' => true,
					'publicly_querable' => false,
					'query_var' => true,
					'capability_type' => 'post',
					'can_export' => true,
					'show_ui' => true,
					'show_in_admin_bar' => true,
					'exclude_from_search' => true,
					'show_in_menu' => 'woocommerce',
					'has_archive' => true,
					'rewrite' => array(
						'slug',
						'auction-woo',
						'with_front' => false,
					),
					'show_in_rest' => true,
				);
				register_post_type('city_bid_for_woo', $options);

				$labels = array(
					'name' => esc_html__('Bid offer product For WooCommerce', 'auction-woo'),
					'singular_name' => esc_html__('Bid offer product For WooCommerce', 'auction-woo'),
					'edit_item' => esc_html__('Edit Bid offer product For WooCommerce ', 'auction-woo'),
					'new_item' => esc_html__('Bid offer product For WooCommerce', 'auction-woo'),
					'view_item' => esc_html__('View Bid offer product For WooCommerce Cart', 'auction-woo'),
					'search_items' => esc_html__('Search Bid offer product For WooCommerce', 'auction-woo'),
					'not_found' => esc_html__('No Bid offer product For WooCommerce found', 'auction-woo'),
					'not_found_in_trash' => esc_html__('No bestprice found in trash', 'auction-woo'),
					'menu_name' => esc_html__('Bidding for Woo', 'auction-woo'),
					'item_published' => esc_html__('Bid offer product For WooCommerce published', 'auction-woo'),
					'item_updated' => esc_html__('Bid offer product For WooCommerce updated', 'auction-woo'),
				);
				$supports = array(
					'title',
				);
				$options = array(
					'supports' => $supports,
					'labels' => $labels,
					'public' => true,
					'publicly_querable' => false,
					'query_var' => true,
					'capability_type' => 'post',
					'can_export' => true,
					'show_ui' => true,
					'show_in_admin_bar' => false,
					'exclude_from_search' => true,
					'show_in_menu' => 'woocommerce',
					'has_archive' => true,
					'rewrite' => array(
						'slug',
						'auction-woo',
						'with_front' => false,
					),
					'show_in_rest' => true,
				);
				register_post_type('offer_product', $options);

			}
		}

		public function ct_bid_cron_schedules( $schedules ) {
			$_type = get_option('ct_bid_cron_job_type');
			$_time = get_option('ct_bid_set_time');

			$cron_time = 5 * 60;

			switch ($_type) {
				case 'minutes':
					$cron_time = $_time * 60;
					break;
				case 'hours':
					$cron_time = $_time * 60 * 60;
					break;
				case 'days':
					$cron_time = $_time * 60 * 60 * 24;
					break;
				case 'seconds':
					$cron_time = $_time;
					break;
			}

			$schedules['af_rfd_cron_schedule'] = array(
				'interval' => $cron_time,
				'display' => __('Bidding Cron Schedule', 'auction-woo'),
			);

			return $schedules;
		}

		public function devsoul_bpg_global_constents_vars() {

			if (!defined('BID_URL')) {
				define('BID_URL', plugin_dir_url(__FILE__));
			}
			if (!defined('BID_BASENAME')) {
				define('BID_BASENAME', plugin_basename(__FILE__));
			}

			if (!defined('BID_PLUGIN_DIR')) {
				define('BID_PLUGIN_DIR', plugin_dir_path(__FILE__));
			}
		}

		public function bidding_domain() {

			if (function_exists('load_plugin_textdomain')) {
				load_plugin_textdomain('auction-woo', false, dirname(plugin_basename(__FILE__)) . '/languages/');
			}
		}
		public function af_bid_email_body_share( $emails ) {

			include_once BID_PLUGIN_DIR . '/includes/email/class_ct_decline_email.php';
			$emails['CT_B_I_D_Lose_Auction_Email'] = new CT_B_I_D_Lose_Auction_Email();
			include_once BID_PLUGIN_DIR . 'includes/email/class_af_b_i_d_auctionwin_email.php';
			$emails['CT_B_I_D_Auction_Win'] = new CT_B_I_D_Auction_Win();
			return $emails;
		}
	}
	new Bidding_For_Woocommerce();
}
