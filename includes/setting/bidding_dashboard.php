<?php
$auction_products = devsoul_get_all_bidding_products();

?>
<div id="filter-container" style="margin-bottom: 20px;">
	<form class="filter-container-content" method="get">
		<label for="start-date">Product:</label>
		<select name="product_id">
			<option value="">Select Product</option>
			<?php
			foreach ($auction_products as $product_id) :
				if (empty($product_id)) {
					continue;
				}
				$selected = selected(isset($_GET['product_id']) && !empty($_GET['product_id']) ? sanitize_text_field($_GET['product_id']) : '', $product_id);
				?>
				<option <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($product_id); ?>">
					<?php echo esc_attr(get_the_title($product_id)); ?></option>
			<?php endforeach ?>
		</select>

		<label for="start-date">Start Date:</label>
		<input type="date"
			value="<?php echo esc_attr(isset($_GET['start-date']) && !empty($_GET['start-date']) ? sanitize_text_field($_GET['start-date']) : ''); ?>"
			name="start-date">

		<label for="end-date" style="margin-left: 10px;">End Date:</label>
		<input type="date"
			value="<?php echo esc_attr(isset($_GET['end-date']) && !empty($_GET['end-date']) ? sanitize_text_field($_GET['end-date']) : ''); ?>"
			name="end-date">

		<select class="ct_dashboard_user_role wc-enhanced-select sel2" name="ct_dashboard_user_role[]" multiple
			style="width:20%;">
			<?php
			foreach ($ct_dashboard_user_role as $user_id) {
				$user_data = get_userdata($user_id);
				if ($user_data) {
					$user_display_name = $user_data->display_name;
					// Optionally display options here
				}
			}
			?>
		</select>

		<input type="submit" class="button button-primary woocommerce-save-button" value="Filter" name="filter_data">
		<input type="submit" class="button button-primary woocommerce-save-button devsoul-afw-clear-filter"
			data-location="dashboard_url" value="Clear">

	</form>
</div>

<div id="bidding-table" style="width: 100%; height: 500px;"></div>
<?php
$date_format = get_option('date_format');

if (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
	$product_id = sanitize_text_field($_GET['product_id']);
	$devsoul_post_data = get_posts(array(
		'numberposts' => -1,
		'post_type'   => 'bidding_for_user',
		'post_status' => 'publish',
		'post_parent' => $product_id,
		'fields'      => 'ids',
	));

	if (!class_exists('WP_List_Table')) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	}

	class Devsoul_Bidding_List_Table extends WP_List_Table {
	
		public $items; // Change this to public

		public function __construct( $items ) {
			parent::__construct(array(
				'singular' => 'bidding_detail',
				'plural'   => 'bidding_details',
				'ajax'     => false,
			));
			$this->items = $items;
		}

		public function get_columns() {
			return array(
				'user'        => __('User', 'auction-woo'),
				'user_email'  => __('User Email', 'auction-woo'),
				'bid_price'   => __('Bid Price', 'auction-woo'),
				'status'      => __('Status', 'auction-woo'),
				'date_time'   => __('Date and Time', 'auction-woo'),
			);
		}

		protected function column_default( $item, $column_name ) {
			switch ($column_name) {
				case 'user':
					return esc_attr($item['user']->display_name);
				case 'user_email':
					return esc_attr($item['user']->user_email);
				case 'bid_price':
					return esc_attr($item['bid_price']);
				case 'status':
					return esc_attr($item['status']);
				case 'date_time':
					return esc_attr($item['date_time']);
				default:
					return '';
			}
		}

		public function prepare_items() {
			$per_page = 10; // Number of items per page
			$current_page = $this->get_pagenum();
			$total_items = count($this->items);

			// Paginate the data array
			$this->items = array_slice($this->items, ( ( $current_page - 1 ) * $per_page ), $per_page);

			// Set up the columns and headers
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = array();

			$this->_column_headers = array( $columns, $hidden, $sortable );

			// Set pagination arguments
			$this->set_pagination_args(array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil($total_items / $per_page),
			));
		}
	}

	$data = array();

	foreach ($devsoul_post_data as $rule_id) {
		$form_data       = (array) get_post_meta($rule_id, 'bpg_formdata', true);
		$ct_bid_price    = isset($form_data['ct_bid_price']) ? $form_data['ct_bid_price'] : 0;
		$user            = get_user_by('ID', isset($form_data['current_user_id']) ? $form_data['current_user_id'] : 0);
		$current_status  = get_post_meta($rule_id, 'ct_bidding_status', true) ? get_post_meta($rule_id, 'ct_bidding_status', true) : 'Pending';
		$date_time       = get_the_date($date_format, $rule_id);

		$data[] = array(
			'user'      => $user,
			'user_email'=> $user->user_email,
			'bid_price' => $ct_bid_price,
			'status'    => $current_status,
			'date_time' => $date_time,
		);
	}

	$bidding_list_table = new Devsoul_Bidding_List_Table($data);
	$bidding_list_table->prepare_items();
	?>

	<i class="button button-primary woocommerce-save-button devsoul-biiding-detail-export-csv-btn"><?php echo esc_html__('Export CSV', 'auction-woo'); ?></i>
	<a class="button button-primary woocommerce-save-button" href="<?php echo esc_url(admin_url('admin.php?page=bidding-settings&tab=dashboard')); ?>"><?php echo esc_html__('Go Back', 'auction-woo'); ?></a>
	<br>
	<form method="post">
		<?php $bidding_list_table->display(); ?>
	</form>

	<?php
	return;
}

$current_user_bidding_detail = devsoul_get_bidding_products_details($auction_products);
devsoul_get_table_of_bidding_detail($current_user_bidding_detail);

?>
</div>
