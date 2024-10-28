<?php
if (!defined('ABSPATH')) {
	exit;
}

add_action('all_admin_notices', 'display_tabs', 5);
add_action('admin_menu', 'ct_bidding_submenu');
add_action('admin_init', 'devsoul_bid_regester_general_setting');
add_action('add_meta_boxes', 'bid_custom_metabox');
add_action('save_post_city_bid_for_woo', 'af_bid_save_metabox');
add_action('admin_enqueue_scripts', 'meta_box_fields_style');
add_filter('woocommerce_product_data_tabs', 'bidding_product_setting');
add_action('woocommerce_product_data_panels', 'ct_wpa_show_panel');
add_action('woocommerce_process_product_meta', 'save_bidding_product_settings');
add_filter('manage_bidding_for_user_posts_columns', 'custom_columns', 10, 1);
add_action('manage_bidding_for_user_posts_custom_column', 'wp_posts_data', 10, 1);
add_action('show_user_profile', 'devsoul_save_custom_user_profile_fields');
add_action('edit_user_profile', 'devsoul_save_custom_user_profile_fields');

function devsoul_save_custom_user_profile_fields( $user ) {


	$current_user_bidding_detail = devsoul_get_bidding_products_details(array(), $user->ID);

	devsoul_get_table_of_bidding_detail($current_user_bidding_detail, $is_for_specific_user = true);
}

function custom_columns( $columns ) {

	unset($columns['title']);
	unset($columns['date']);
	unset($columns['Add New']);
	$columns['product_detail'] = 'Product';
	$columns['price'] = 'Bid Price';
	$columns['user'] = 'User';
	$columns['Email'] = 'Email';
	$columns['acknowledge'] = 'Acknowledge Email';
	$columns['date'] = 'Date';



	return $columns;
}


function wp_posts_data( $column ) {

	$product_id = wp_get_post_parent_id(get_the_ID());
	$product = get_the_title($product_id);
	$form_data = (array) get_post_meta(get_the_ID(), 'bpg_formdata', true);
	$user = get_user_by('ID', isset($form_data['current_user_id']) ? $form_data['current_user_id'] : 0);



	switch ($column) {
		case 'product_detail':
			?>
		<a href="<?php echo esc_url(get_edit_post_link($product_id)); ?>"
			target="_blank"><?php echo esc_attr(get_the_title($product_id)); ?></a>
			<?php
			break;

		case 'price':
			echo esc_attr(isset($form_data['ct_bid_price']) ? $form_data['ct_bid_price'] : 0);
			break;
		case 'user':
			if ($user) {
				echo esc_attr($user->display_name);
			}

			break;
		case 'acknowledge':
			?>
			<div class="">
				<button type="button" class="button-primary ct_bidding_send_email_data"
				name="ct_approve_email"><?php echo esc_html__('Approve', 'auction-woo'); ?></button>
				<button type="button" class="ct_disapprove_email_button ct_dissapprove_coloum"
				name="ct_dissapprove_email"><?php echo esc_html__('Disapprove', 'auction-woo'); ?></button>
			</div>
			<?php
			break;

		case 'Email':
			if ($user) {
				echo esc_attr($user->user_email);
			}
			break;

		default:
			break;



	}
}
function bidding_emails_user() {
	wp_nonce_field('ct_bid_meta_nonce', 'ct_bid_meta_nonce_field');
	include BID_PLUGIN_DIR . 'includes/admin/metbox/bid_email_meta.php';
}



function bidding_product_setting( $tabs ) {
	$tabs['ct_bid_setting'] = array(
		'label' => __('Bidding Settings', 'woocommerce'),
		'priority' => 70,
		'target' => 'ct_bid_setting',
	);

	return $tabs;
}

function ct_wpa_show_panel() {
	global $post;
	$dev_enabel_auction_product = get_post_meta($post->ID, 'dev_enabel_auction_product', true);


	?>
		<div class="panel woocommerce_options_panel hidden" id="ct_bid_setting">

			<div class="options_group">
				<a class="fa fa-eye" target="_blank"
				href="<?php echo esc_url(admin_url('admin.php?page=bidding-settings&tab=dashboard&product_id=' . $post->ID)); ?>">View
			Product Detail</a>
			<p class="form-field">
				<label
				for="dev_enabel_auction_product"><?php echo esc_html__('Enable product bidding', 'auction-woo'); ?></label>
				<input type="checkbox" name="dev_enabel_auction_product" id="dev_enabel_auction_product" value="yes" <?php checked('yes' == $dev_enabel_auction_product); ?>>
			</p>
		<?php bid_setting_meta_box($post); ?>


		</div>
	</div>

	<?php
}


function save_bidding_product_settings( $post_id ) {
	if (!current_user_can('edit_posts')) {
		return;
	}

	// For custom post type:
	$exclude_statuses = array(
		'auto-draft',
		'trash',
	);
	$current_post_action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';

	if (!in_array(get_post_status($post_id), $exclude_statuses) && !is_ajax() && 'untrash' != $current_post_action) {

		$nonce = isset($_POST['bid_meta_nonce_field']) ? sanitize_text_field(wp_unslash($_POST['bid_meta_nonce_field'])) : 0;

		if (!wp_verify_nonce($nonce, 'bid_meta_nonce')) {
			wp_die('Failed ajax security');
		}

		if (isset($_POST['ct_bid_user_role'])) {
			$sanitized_bid_user_role = array_map('sanitize_text_field', $_POST['ct_bid_user_role']);
			update_post_meta($post_id, 'ct_bid_user_role', serialize($sanitized_bid_user_role));
		}

		if (isset($_POST['ct_start_bidding_price'])) {
			update_post_meta($post_id, 'ct_start_bidding_price', sanitize_text_field($_POST['ct_start_bidding_price']));
		}

		if (isset($_POST['ct_bid_start_date'])) {
			update_post_meta($post_id, 'ct_bid_start_date', sanitize_text_field($_POST['ct_bid_start_date']));
		}

		if (isset($_POST['ct_bid_start_time'])) {
			update_post_meta($post_id, 'ct_bid_start_time', sanitize_text_field($_POST['ct_bid_start_time']));
		}

		if (isset($_POST['ct_bid_type'])) {
			update_post_meta($post_id, 'ct_bid_type', sanitize_text_field($_POST['ct_bid_type']));
		}

		if (isset($_POST['ct_bid_min_increment'])) {
			update_post_meta($post_id, 'ct_bid_min_increment', sanitize_text_field($_POST['ct_bid_min_increment']));
		}

		if (isset($_POST['ct_text_when_end'])) {
			update_post_meta($post_id, 'ct_text_when_end', sanitize_text_field($_POST['ct_text_when_end']));
		}

		if (isset($_POST['ct_reserve_price'])) {
			update_post_meta($post_id, 'ct_reserve_price', sanitize_text_field($_POST['ct_reserve_price']));
		}

		if (isset($_POST['ct_bid_end_time'])) {
			update_post_meta($post_id, 'ct_bid_end_time', sanitize_text_field($_POST['ct_bid_end_time']));
		}

		if (isset($_POST['ct_bid_end_date'])) {
			update_post_meta($post_id, 'ct_bid_end_date', sanitize_text_field($_POST['ct_bid_end_date']));
		}

		if (isset($_POST['dev_enabel_auction_product'])) {
			update_post_meta($post_id, 'dev_enabel_auction_product', sanitize_text_field($_POST['dev_enabel_auction_product']));
		}
	}
}
function meta_box_fields_style() {

	wp_enqueue_media();
	wp_enqueue_style('bid-admin', BID_URL . '/assest/css/bid_metabox_styling.css', array(), '1.0.0');
	wp_enqueue_script('bid_admin_js', BID_URL . '/assest/js/admin.js', array(), '1.0.0');
	wp_enqueue_script('loader-js', 'https://www.gstatic.com/charts/loader.js', array(), '1.0.0', true);
	wp_enqueue_style('font-awesome-admin', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), null);


	wp_enqueue_style('wc-select2', plugins_url('assets/css/select2.css', WC_PLUGIN_FILE), array(), '5.7.2');
	wp_enqueue_script('wc-select2', plugins_url('assets/js/select2/select2.min.js', WC_PLUGIN_FILE), array( 'jquery' ), '4.0.3', true);

	$aurgs = array(
		'admin_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('ct_dfw_nonce'),
		'dashboard_url' => admin_url('admin.php?page=bidding-settings&tab=dashboard'),
	);

	wp_localize_script('bid_admin_js', 'ct_bidd_data', $aurgs);


	$auction_products = isset($_GET['product_id']) && !empty($_GET['product_id']) ? array( sanitize_text_field($_GET['product_id']) ) : devsoul_get_all_bidding_products();



	$bidding_detail = array( array( 'Product Name', 'Highest Bid', 'Status' ) );
	$date_format = get_option('date_format');
	if (isset($_GET['product_id']) && !empty($_GET['product_id'])) {

		$show_data = 'date_according';

		$bidding_detail = array( array( 'Date', 'Bid' ) );
		$product_id = sanitize_text_field($_GET['product_id']);

		$devsoul_post_data = get_posts(
			array(
				'numberposts' => -1,
				'post_type' => 'bidding_for_user',
				'post_status' => 'publish',
				'post_parent' => $product_id,
				'fields' => 'ids',
			)
		);

		$date_format = get_option('date_format');

		foreach ($devsoul_post_data as $rule_id) {
			$form_data = (array) get_post_meta($rule_id, 'bpg_formdata', true);
			$status = get_post_meta($rule_id, 'ct_bidding_status', true) ? get_post_meta($rule_id, 'ct_bidding_status', true) : 'Pending';
			$bid_price = isset($form_data['ct_bid_price']) ? $form_data['ct_bid_price'] : 0;

			$bidding_detail[] = array( (string) get_the_date('Y-m-d', $rule_id), (float) $bid_price );

		}
		if (count($bidding_detail) == 1) {
			$bidding_detail[] = array( (string) gmdate('Y-m-d'), 0 );

		}
	} else {
		$show_data = 'all_products';

		foreach ($auction_products as $product_id) {
			$product = wc_get_product($product_id);

			if (!$product) {
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
			foreach ($devsoul_post_data as $rule_id) {
				$form_data = (array) get_post_meta($rule_id, 'bpg_formdata', true);
				$total_spend_amount[ $rule_id ] = isset($form_data['ct_bid_price']) ? $form_data['ct_bid_price'] : 0;
			}
			$max_bid_rule_id = array_search(max($total_spend_amount), $total_spend_amount);

			$status = get_post_meta($max_bid_rule_id, 'ct_bidding_status', true) ? get_post_meta($max_bid_rule_id, 'ct_bidding_status', true) : 'Pending';


			$bidding_detail[] = array( $product->get_name(), max($total_spend_amount), $status );
		}
	}

	if ('general_setting' == get_current_tab()) {

		wp_enqueue_script('bid_report_js', BID_URL . '/assest/js/report.js', array(), '1.0.0');
		$aurgs = array(
			'admin_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ct_dfw_nonce'),
			'bidding_detail' => $bidding_detail,
			'show_data' => $show_data,
		);
		wp_localize_script('bid_report_js', 'php_var', $aurgs);
	}
}
function af_bid_save_metabox( $post_id ) {

	if (!current_user_can('edit_posts')) {
		return;
	}

	if ('auto-draft' == get_post_status($post_id) || 'trash' == get_post_status($post_id)) {
		return;
	}
	$nonce = isset($_POST['bid_meta_nonce_field']) ? sanitize_text_field(wp_unslash($_POST['bid_meta_nonce_field'])) : 0;

	if (!wp_verify_nonce($nonce, 'bid_meta_nonce')) {
		wp_die('Failed ajax security');
	}

	$ct_bid_min_increment = isset($_POST['ct_bid_min_increment']) ? sanitize_text_field($_POST['ct_bid_min_increment']) : '';
	update_post_meta($post_id, 'ct_bid_min_increment', $ct_bid_min_increment);

	$ct_reserve_price = isset($_POST['ct_reserve_price']) ? sanitize_text_field($_POST['ct_reserve_price']) : '';
	update_post_meta($post_id, 'ct_reserve_price', $ct_reserve_price);

	$ct_start_bidding_price = isset($_POST['ct_start_bidding_price']) ? sanitize_text_field($_POST['ct_start_bidding_price']) : '';
	update_post_meta($post_id, 'ct_start_bidding_price', $ct_start_bidding_price);

	$ct_bid_start_date = isset($_POST['ct_bid_start_date']) ? sanitize_text_field($_POST['ct_bid_start_date']) : '';
	update_post_meta($post_id, 'ct_bid_start_date', $ct_bid_start_date);

	$ct_bid_start_time = isset($_POST['ct_bid_start_time']) ? sanitize_text_field($_POST['ct_bid_start_time']) : '';
	update_post_meta($post_id, 'ct_bid_start_time', $ct_bid_start_time);


	$ct_bid_end_date = isset($_POST['ct_bid_end_date']) ? sanitize_text_field($_POST['ct_bid_end_date']) : '';
	update_post_meta($post_id, 'ct_bid_end_date', $ct_bid_end_date);

	$ct_text_when_end = isset($_POST['ct_text_when_end']) ? sanitize_text_field($_POST['ct_text_when_end']) : '';
	update_post_meta($post_id, 'ct_text_when_end', $ct_text_when_end);


	$ct_bid_end_time = isset($_POST['ct_bid_end_time']) ? sanitize_text_field($_POST['ct_bid_end_time']) : '';
	update_post_meta($post_id, 'ct_bid_end_time', $ct_bid_end_time);


	$af_bids_review_products = isset($_POST['af_bid_review_products']) ? sanitize_meta('', $_POST['af_bid_review_products'], '') : array();

	update_post_meta($post_id, 'af_bid_review_products', $af_bids_review_products);

	$af_bid_category_review = isset($_POST['af_bid_category_review']) ? sanitize_meta('', $_POST['af_bid_category_review'], '') : array();

	update_post_meta($post_id, 'af_bid_category_review', $af_bid_category_review);


	$ct_bid_type = isset($_POST['ct_bid_type']) ? sanitize_meta('', $_POST['ct_bid_type'], '') : array();

	update_post_meta($post_id, 'ct_bid_type', $ct_bid_type);

	$ct_bid_user_role = isset($_POST['ct_bid_user_role']) ? sanitize_meta('', $_POST['ct_bid_user_role'], '') : array();

	update_post_meta($post_id, 'ct_bid_user_role', $ct_bid_user_role);
}
function devsoul_bid_regester_general_setting() {

	include BID_PLUGIN_DIR . 'includes/admin/metbox/column.php';
	include BID_PLUGIN_DIR . 'includes/setting/class_ct_b_i_d_general.php';
	include BID_PLUGIN_DIR . 'includes/setting/cron_job_setting.php';
	include BID_PLUGIN_DIR . 'includes/setting/auction_settings.php';
	include BID_PLUGIN_DIR . 'includes/setting/customization_settings.php';
	include BID_PLUGIN_DIR . 'includes/setting/offer-product.php';
}
function bid_custom_metabox() {

	add_meta_box(
		'bid_box_id',          // Unique ID
		'Bidding Detail',   //Name
		'bid_setting_meta_box',
		'city_bid_for_woo' //post types here
	);

	add_meta_box(
		'submitted_product_detail_id',          // Unique ID
		'Submitted Product Detail',   //Name
		'submitted_product_detail_id',
		'offer_product' //post types here
	);
	add_meta_box(
		'bid_email_id',
		'Bidders Detail',
		'bidding_emails_user',
		'bidding_for_user'

	);
	remove_meta_box('submitdiv', array( 'offer_product', 'bidding_for_user' ), 'side');
}
function bid_setting_meta_box( $post ) {
	wp_nonce_field('bid_meta_nonce', 'bid_meta_nonce_field');


global $wp_roles;

$roles = $wp_roles->get_names();
$current_post_id = $post->ID;
$af_bid_review_products = (array) get_post_meta($post->ID, 'af_bid_review_products', true);
$af_bid_category_review = (array) get_post_meta($post->ID, 'af_bid_category_review', true);
$ct_start_bidding_price = get_post_meta($post->ID, 'ct_start_bidding_price', true);
$ct_bid_start_date = get_post_meta($post->ID, 'ct_bid_start_date', true);
$ct_bid_start_time = get_post_meta($post->ID, 'ct_bid_start_time', true);
$ct_text_when_end = get_post_meta($post->ID, 'ct_text_when_end', true);
$ct_bid_type = (array) get_post_meta($post->ID, 'ct_bid_type', true);
$ct_bid_min_increment = get_post_meta($post->ID, 'ct_bid_min_increment', true);
$ct_reserve_price = get_post_meta($post->ID, 'ct_reserve_price', true);
$ct_bid_user_role = (array) get_post_meta($post->ID, 'ct_bid_user_role', true);
$ct_bid_end_date = get_post_meta($post->ID, 'ct_bid_end_date', true);
$ct_bid_end_time = get_post_meta($post->ID, 'ct_bid_end_time', true);


	if ( 'product' == get_post_type($post->ID) ) {
		woocommerce_wp_select(array(
			'class'             => 'ct_wo_bid_user_role',
			'id'                => 'ct_bid_user_role',
			'label'             => esc_html__('Select User Role', 'auction-woo'),
			'name'              => 'ct_bid_user_role[]',
			'options'           => array_merge(array( '' => 'Select' ), array_map(function ( $role ) {
			return esc_html($role); }, $roles)),
			'value'             => $ct_bid_user_role,
			'custom_attributes' => array( 'multiple' => 'multiple' ),
			'wrapper_class'     => 'form-row form-row-wide wc-enhanced-select sel2',
		));

		woocommerce_wp_select(array(
			'id'                => 'ct_bid_type',
			'label'             => esc_html__('Bid Type', 'auction-woo'),
			'name'              => 'ct_bid_type',
			'options'           => array(
				'mannual'   => esc_html__('Manual', 'auction-woo'),
				'automatic' => esc_html__('Automatic', 'auction-woo'),
			),
			'value'             => $ct_bid_type,
			'wrapper_class'     => 'form-row form-row-wide ct_bidding_type_man',
		));

		woocommerce_wp_text_input(array(
			'id'                => 'ct_bid_min_increment',
			'label'             => esc_html__('Minimum Increment', 'auction-woo'),
			'type'              => 'number',
			'value'             => esc_attr($ct_bid_min_increment),
			'wrapper_class'     => 'form-row form-row-wide',
		));

		woocommerce_wp_text_input(array(
			'id'                => 'ct_start_bidding_price',
			'label'             => esc_html__('Starting Bid Price', 'auction-woo'),
			'type'              => 'number',
			'value'             => esc_attr($ct_start_bidding_price),
			'wrapper_class'     => 'form-row form-row-wide',
		));

		woocommerce_wp_text_input(array(
			'id'                => 'ct_reserve_price',
			'label'             => esc_html__('Reserve Price', 'auction-woo'),
			'type'              => 'number',
			'value'             => esc_attr($ct_reserve_price),
			'wrapper_class'     => 'form-row form-row-wide',
		));

		woocommerce_wp_text_input(array(
			'id'                => 'ct_bid_start_date',
			'label'             => esc_html__('Auction Start Date', 'auction-woo'),
			'type'              => 'date',
			'value'             => esc_attr($ct_bid_start_date),
			'wrapper_class'     => 'form-row form-row-wide',
		));

		woocommerce_wp_text_input(array(
			'id'                => 'ct_bid_start_time',
			'label'             => esc_html__('Auction Start Time', 'auction-woo'),
			'type'              => 'time',
			'value'             => esc_attr($ct_bid_start_time),
			'wrapper_class'     => 'form-row form-row-wide',
		));

		woocommerce_wp_text_input(array(
			'id'                => 'ct_bid_end_date',
			'label'             => esc_html__('Auction End Date', 'auction-woo'),
			'type'              => 'date',
			'value'             => esc_attr($ct_bid_end_date),
			'wrapper_class'     => 'form-row form-row-wide',
		));

		woocommerce_wp_text_input(array(
			'id'                => 'ct_bid_end_time',
			'label'             => esc_html__('Auction End Time', 'auction-woo'),
			'type'              => 'time',
			'value'             => esc_attr($ct_bid_end_time),
			'wrapper_class'     => 'form-row form-row-wide',
		));

		woocommerce_wp_text_input(array(
			'id'                => 'ct_text_when_end',
			'label'             => esc_html__('Text to Show When Auction Ends', 'auction-woo'),
			'type'              => 'text',
			'value'             => esc_attr($ct_text_when_end),
			'wrapper_class'     => 'form-row form-row-wide',
		));

		return;
	}
	include BID_PLUGIN_DIR . 'includes/admin/metbox/bid_meta_box.php';
}
function submitted_product_detail_id() {
	wp_nonce_field('bid_meta_nonce', 'bid_meta_nonce_field');
	include BID_PLUGIN_DIR . 'includes/admin/metbox/submitted-product-detail-id.php';
}

function ct_bidding_submenu() {
	add_submenu_page(
		'woocommerce',
		'Bidding for Woo',
		'Bidding for Woo',
		'manage_options',
		'bidding-settings',
		'devsoul_bfw_render_custom_submenu' // callback
	);

	global $pagenow, $typenow;

	if (
		( ( 'edit.php' === $pagenow || 'post-new.php' === $pagenow ) && 'bidding_for_user' === $typenow )
		|| ( 'post.php' === $pagenow && isset($_GET['post']) && 'bidding_for_user' === get_post_type(sanitize_text_field($_GET['post'])) )
	) {
		remove_submenu_page('woocommerce', 'bidding-settings');
		remove_submenu_page('woocommerce', 'edit.php?post_type=city_bid_for_woo');
		remove_submenu_page('woocommerce', 'edit.php?post_type=offer_product');


	} elseif (
		( ( 'edit.php' === $pagenow || 'post-new.php' === $pagenow ) && 'city_bid_for_woo' === $typenow )
		|| ( 'post.php' === $pagenow && isset($_GET['post']) && 'city_bid_for_woo' === get_post_type(sanitize_text_field($_GET['post'])) )
	) {
		remove_submenu_page('woocommerce', 'bidding-settings');
		remove_submenu_page('woocommerce', 'edit.php?post_type=bidding_for_user');
		remove_submenu_page('woocommerce', 'edit.php?post_type=offer_product');


	} elseif (
		( ( 'edit.php' === $pagenow || 'post-new.php' === $pagenow ) && 'offer_product' === $typenow )
		|| ( 'post.php' === $pagenow && isset($_GET['post']) && 'offer_product' === get_post_type(sanitize_text_field($_GET['post'])) )
	) {
		remove_submenu_page('woocommerce', 'bidding-settings');
		remove_submenu_page('woocommerce', 'edit.php?post_type=bidding_for_user');
		remove_submenu_page('woocommerce', 'edit.php?post_type=city_bid_for_woo');


	} elseif (( 'admin.php' === $pagenow && isset($_GET['page']) && 'bidding-settings' === sanitize_text_field($_GET['page']) )) {

		remove_submenu_page('woocommerce', 'edit.php?post_type=bidding_for_user');
		remove_submenu_page('woocommerce', 'edit.php?post_type=city_bid_for_woo');
		remove_submenu_page('woocommerce', 'edit.php?post_type=offer_product');


	} else {
		remove_submenu_page('woocommerce', 'edit.php?post_type=bidding_for_user');
		remove_submenu_page('woocommerce', 'bidding-settings');
		remove_submenu_page('woocommerce', 'edit.php?post_type=offer_product');

	}
}
// Callback function to render the submenu page
function devsoul_bfw_render_custom_submenu() {
	$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'dashboard';
	$section = isset($_GET['section']) ? sanitize_text_field($_GET['section']) : 'general_setting';

	?>
	<div class="wrap">
		<form method="post" action="options.php">
			<?php
			settings_errors();
			switch ($active_tab) {
				case 'general_setting':
					if ('general_setting' == $section) {

						settings_fields('devsoul_bfw_general_settings_fields');
						do_settings_sections('devsoul_bfw_general_settings_sections');
						submit_button();

					}

					if ('cron_job_setting' == $section) {
						settings_fields('devsoul_bid_cron_settings_fields');
						do_settings_sections('devsoul_bid_cron_settings_sections');
						submit_button();
					}

					if ('auction_page' == $section) {

						settings_fields('devsoul_bid_auction_page_settings_fields');
						do_settings_sections('devsoul_bid_auction_page_settings_sections');
						submit_button();

					}
					if ('customization' == $section) {

						settings_fields('devsoul_bid_customize_settings_fields');
						do_settings_sections('devsoul_bid_customize_settings_sections');
						submit_button();
					}

					if ('user_offer_product_setting' == $section) {
						settings_fields('devsoul_bid_user_offer_product_fields');
						do_settings_sections('devsoul_bid_user_offer_product_sections');
						submit_button();
					}

					break;

				case 'Bid_all_user':
				settings_fields('devsoul_bid_Bid_all_user_fields');
				do_settings_sections('devsoul_bid_Bid_all_user_sections');
				submit_button();
					break;
			}

			?>

		</form>
		<?php

		if ('dashboard' == $active_tab) {
			include BID_PLUGIN_DIR . 'includes/setting/bidding_dashboard.php';

		}
		if ('shortcode' == $section) {
			?>
			<table class="wp-list-table widefat fixed striped table-view-list">
				<tbody>
					<tr>
						<th><?php esc_html_e('Shortcode for Bidding', 'auction-woo'); ?></th>
						<td>
							[devsoul_show_bidding_all_products]
							<p>Place this shortcode to show all products on which bidding is avaiable</p>
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e('Shortcode for Bidding form', 'auction-woo'); ?></th>
						<td>
							[devsoul_biiding_shortcode product_id="123"]
						</td>
					</tr>
					<tr>
						<td><?php echo esc_html_e('Short code to show form for offer product', 'auction-woo'); ?>
					</td>
					<td>[devsoul_show_product_offer_form]</td>
				</tr>
			</tbody>
		</tbody>
	</table>
	<?php
		}
		?>
</div>

<?php
}

function display_tabs() {
	global $post, $typenow;
	$screen = get_current_screen();
	if ($screen && in_array($screen->id, get_tab_screen_ids(), true)) {
		// List of your tabs.
		$tabs = array(

			'city_bid_for_woo' => array(
				'title' => __('Bidding Rules', 'auction-woo'), // title of tab
				'url' => admin_url('edit.php?post_type=city_bid_for_woo'), // Url of tab
			),
			'dashboard' => array(
				'title' => __('Dashboard', 'auction-woo'),
				'url' => admin_url('admin.php?page=bidding-settings&tab=dashboard'),
			),
			'general_setting' => array(
				'title' => __('General Settings', 'auction-woo'), // title of tab
				'url' => admin_url('admin.php?page=bidding-settings&tab=general_setting'), // Url of tab
			),
			'bidding_for_user' => array(
				'title' => __('Users Bids', 'auction-woo'), // title of tab
				'url' => admin_url('edit.php?post_type=bidding_for_user'), // Url of tab
			),
			'offer_product' => array(
				'title' => __('User Offered Product', 'auction-woo'), // title of tab
				'url' => admin_url('edit.php?post_type=offer_product'), // Url of tab
			),
		);

		if (is_array($tabs)) {
			?>
			<div class="wrap woocommerce">
				<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
					<?php
					$current_tab = get_current_tab();
					if ('general_setting' == $current_tab) {
						$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'dashboard';
					}
					foreach ($tabs as $id => $tab_data) {
						$class = $id === $current_tab ? array( 'nav-tab', 'nav-tab-active' ) : array( 'nav-tab' );
						printf('<a href="%1$s" class="%2$s">%3$s</a>', esc_url($tab_data['url']), implode(' ', array_map('sanitize_html_class', $class)), esc_html($tab_data['title']));
					}
					?>
				</h2>

				<?php
				if ('general_setting' == $current_tab) {

					$active_section = isset($_GET['section']) ? sanitize_text_field($_GET['section']) : 'general_setting';
					$section = array(
						'general_setting' => 'General Settings',
						'shortcode' => 'Shortcode',
						'cron_job_setting' => 'Cron Job Settings',
						'customization' => 'Customization',
						'auction_page' => 'Auction Page',
						'user_offer_product_setting' => 'User Offered Product Settings',
					);
					?>
					<ul class="subsubsub">

						<?php
						foreach ($section as $key => $value) {

							$li_class = $active_section == $key ? 'current' : '';
							?>
							<li>
								<a href="<?php echo esc_url(admin_url('admin.php?page=bidding-settings&tab=general_setting&section=' . $key)); ?>"
									class="<?php echo esc_attr($li_class); ?>"><?php echo esc_attr($value); ?></a>
									<?php
									echo esc_attr(end($section) != $value ? ' | ' : '');
									?>
								</li>

							<?php } ?>

						</ul>
						<?php
				}

				?>
				</div>
				<?php
		}
	}
}
function get_tab_screen_ids() {
	$tabs_screens = array(
		'city_bid_for_woo',
		'edit-city_bid_for_woo',
		'pages',
		'edit-pages',
		'bidding_for_user',
		'edit-bidding_for_user',
		'woocommerce_page_bidding-settings',
		'offer_product',
		'edit-offer_product',
	);
	return $tabs_screens;
}


function get_current_tab() {
	$screen = get_current_screen();
	$screen_id = $screen->id;

	switch ($screen_id) {
		case 'edit-city_bid_for_woo':
		case 'city_bid_for_woo':
			return 'city_bid_for_woo';
		case 'woocommerce_page_bidding-settings':
			return 'general_setting';
		case 'cron_job_setting':
			return 'cron_job_setting';
		case 'auction_page':
			return 'auction_page';
		case 'customization':
			return 'customization';
		case 'bidding_for_user':
		case 'edit-bidding_for_user':
			return 'bidding_for_user';
		case 'edit-offer_product':
		case 'offer_product':
			return 'offer_product';


	}
}