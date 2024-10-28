<div class="af_rfd_main">
	<table class="wp-list-table widefat fixed striped table-view-list">
		<tr>
			<th> <?php echo esc_html__('Select User role', 'auction-woo'); ?>
			</th>
			<td>
				<select class="ct_bid_serch_user wc-enhanced-select sel2" name="ct_bid_user_role[]"
					id="ct_bid_user_role" multiple style="width: 80%" ;>
					<?php
					global $wp_roles;
					$roles = $wp_roles->get_names();
					foreach ($roles as $key => $value) {
						?>
						<option value="<?php echo esc_attr($key); ?>" 
												  <?php
													if (!empty($ct_bid_user_role) && in_array($key, $ct_bid_user_role)) {
														echo 'selected';
													}
													?>
						   >
							<?php
							echo esc_attr($value);
							?>

						</option>
					<?php } ?>

				</select>
			</td>
		</tr>
		<?php if ('city_bid_for_woo' == get_post_type($post->ID)) { ?>
			<tr>
				<th><?php echo esc_html__('Products', 'af_rfd_review_for_discount'); ?></th>
				<td><select
						class="af_cp_included_product_list_section ct_dfw_product_live_search af_cp_included_product_list af_cp_select_prd af-cp-prd-scroll"
						name="af_bid_review_products[]" multiple style="width: 80%;">
						<?php
						foreach ((array) $af_bid_review_products as $product_id) {
							if ($product_id) {
								$product = wc_get_product($product_id);
								if (!$product) {
									continue;
								}
								?>
								<option value="<?php echo esc_attr($product_id); ?>" selected>
									<?php echo esc_attr(wc_get_product($product_id)->get_name()); ?>
								</option>
								<?php

							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e('categories', 'af_rfd_review_for_discount'); ?></th>
				<td>
					<select class="ct_dfw_categroy_live_search af-cp-prd-scroll" name="af_bid_category_review[]" multiple
						style="width:80%;">
						<?php
						foreach ((array) $af_bid_category_review as $value) {
							if ($value) {
								$term_name = get_term($value)->name;

								?>
								<option value="<?php echo esc_attr($value); ?>" selected><?php echo esc_attr($term_name); ?>
								</option>
								<?php

							}
						}
						?>
					</select>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<th><?php echo esc_html__('Bid Type', 'auction-woo'); ?></th>
			<td>
				<select class="ct_bidding_type_man" name="ct_bid_type">
					<option value="mannual" <?php selected('mannual' == $ct_bid_type); ?>>
						<?php echo esc_html__('Mannual', 'auction-woo'); ?>
					</option>
					<option value="automatic" <?php selected('automatic' == $ct_bid_type); ?>>
						<?php echo esc_html__('Automatic', 'auction-woo'); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr class="ct_bidding_inc" style="display: none;">
			<th>
				<?php echo esc_html__('Minimum Increment', 'auction-woo'); ?>
			</th>
			<td><input type="number" name="ct_bid_min_increment"
					value="<?php echo esc_attr($ct_bid_min_increment); ?>"></input></td>
		</tr>
		<tr>
			<th><?php echo esc_html__('Starting Bid Price', 'auction-woo'); ?></th>
			<td>
				<input type="number" name="ct_start_bidding_price"
					value="<?php echo esc_attr($ct_start_bidding_price); ?>"></input>
			</td>
		</tr>
		<tr>
			<th><?php echo esc_html__('Reserve Price', 'auction-woo'); ?></th>
			<td><input type="number" name="ct_reserve_price" value="<?php echo esc_attr($ct_reserve_price); ?>"></input>
			</td>
		</tr>
		<tr>

			<th><?php echo esc_html__('Auction Start', 'auction-woo'); ?></th>
			<td>
				<input type="date" name="ct_bid_start_date" value="<?php echo esc_attr($ct_bid_start_date); ?>">
				<input type="time" name="ct_bid_start_time" value="<?php echo esc_attr($ct_bid_start_time); ?>">
			</td>
		</tr>
		<tr>
			<th><?php echo esc_html__('Auction End', 'auction-woo'); ?></th>
			<td>
				<input type="date" name="ct_bid_end_date" value="<?php echo esc_attr($ct_bid_end_date); ?>">
				<input type="time" name="ct_bid_end_time" value="<?php echo esc_attr($ct_bid_end_time); ?>">
			</td>
		</tr>


		<tr>
			<th><?php echo esc_html__('Text to show when auction end', 'auction-woo'); ?></th>
			<td><input type="text" name="ct_text_when_end" value="<?php echo esc_attr($ct_text_when_end); ?>"></input>
			</td>
		</tr>

	</table>
</div>