<?php 

$product_id         = wp_get_post_parent_id(get_the_ID());
$product            = get_the_title($product_id);
$form_data          = (array) get_post_meta(get_the_ID(), 'bpg_formdata', true);
$user               = get_user_by('ID', isset($form_data['current_user_id']) ? $form_data['current_user_id'] : 0 );

?> 
<div class="ct_meta_email">
	<table class="form-table">
		<tr>
			<th scope="row"><?php echo esc_html__('Status', 'auction-woo'); ?></th>
			<td><?php echo esc_html(get_post_meta(get_the_ID(), 'ct_bidding_status', true) ? get_post_meta(get_the_ID(), 'ct_bidding_status', true) : 'Pending'); ?></td>
		</tr>
		<tr>
			<th scope="row"><?php echo esc_html__('Product Name', 'auction-woo'); ?></th>
			<td><?php echo esc_html($product); ?></td>
		</tr>
		<tr>
			<th scope="row"><?php echo esc_html__('Bid Price', 'auction-woo'); ?></th>
			<td><?php echo esc_html(isset($form_data['ct_bid_price']) ? $form_data['ct_bid_price'] : 0); ?></td>
		</tr>
		<tr>
			<th scope="row"><?php echo esc_html__('User Name', 'auction-woo'); ?></th>
			<td>
				<?php
				if ($user) {
					echo esc_html($user->display_name);
				}
				?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php echo esc_html__('User Email', 'auction-woo'); ?></th>
			<td>
				<?php
				if ($user) {
					echo esc_html($user->user_email);
				}
				?>
			</td>
		</tr>
	</table>

	<!-- Buttons -->
	<div class="ct_appr_dis_button">
		<button type="button" class="button button-primary ct_bidding_send_email_data" data-type="Approve" data-current_post_id="<?php echo esc_attr(get_the_ID()); ?>" name="ct_approve_email">
			<?php echo esc_html__('Approve', 'auction-woo'); ?>
		</button>
		<button type="button" class="button ct_disapprove_email_button" data-type="Disapprove" data-current_post_id="<?php echo esc_attr(get_the_ID()); ?>" name="ct_dissapprove_email">
			<?php echo esc_html__('Disapprove', 'auction-woo'); ?>
		</button>
	</div>
</div>
