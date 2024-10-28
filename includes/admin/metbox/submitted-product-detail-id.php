
<?php


$first_name = get_post_meta(get_the_ID(), 'first_name', true);
$last_name = get_post_meta(get_the_ID(), 'last_name', true);
$email_address = get_post_meta(get_the_ID(), 'email_address', true);
$phone_number = get_post_meta(get_the_ID(), 'phone_number', true);
$product_year = get_post_meta(get_the_ID(), 'product_year', true);
$product_make = get_post_meta(get_the_ID(), 'product_make', true);
$product_model = get_post_meta(get_the_ID(), 'product_model', true);
$product_type = get_post_meta(get_the_ID(), 'product_type', true);
$product_state = get_post_meta(get_the_ID(), 'product_state', true);
$product_city = get_post_meta(get_the_ID(), 'product_city', true);
$dealer = get_post_meta(get_the_ID(), 'dealer', true);
$product_videos = get_post_meta(get_the_ID(), 'product_videos', true);
$product_details = get_post_meta(get_the_ID(), 'product_details', true);
$product_photos = get_post_meta(get_the_ID(), 'product_photos', true); // Assuming it's an array
$accept_terms = get_post_meta(get_the_ID(), 'accept_terms', true);


?>
<table>
		<tr>
			<td><label for="first_name">First Name *</label></td>
			<td><input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($first_name); ?>" readonly></td>
		</tr>
		<tr>
			<td><label for="last_name">Last Name *</label></td>
			<td><input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($last_name); ?>" readonly></td>
		</tr>
		<tr>
			<td><label for="email_address">Email Address *</label></td>
			<td><input type="email" id="email_address" name="email_address" value="<?php echo esc_attr($email_address); ?>" readonly></td>
		</tr>
		<tr>
			<td><label for="phone_number">Phone Number *</label></td>
			<td><input type="tel" id="phone_number" name="phone_number" value="<?php echo esc_attr($phone_number); ?>" readonly></td>
		</tr>
		<tr>
			<td><label for="product_year">What year is your product?</label></td>
			<td><input type="text" id="product_year" name="product_year" value="<?php echo esc_attr($product_year); ?>" readonly></td>
		</tr>
		<tr>
			<td><label for="product_make">What make is this product? *</label></td>
			<td><input type="text" id="product_make" name="product_make" value="<?php echo esc_attr($product_make); ?>" readonly></td>
		</tr>
		<tr>
			<td><label for="product_model">What model is this product? *</label></td>
			<td><input type="text" id="product_model" name="product_model" value="<?php echo esc_attr($product_model); ?>" readonly></td>
		</tr>
		<tr>
			<td><label for="product_type">What type is this product?</label></td>
			<td><input type="text" id="product_type" name="product_type" value="<?php echo esc_attr($product_type); ?>" readonly></td>
		</tr>
		<tr>
			<td><label for="product_state">What state is the product currently located in? *</label></td>
			<td><input type="text" id="product_state" name="product_state" value="<?php echo esc_attr($product_state); ?>" readonly></td>
		</tr>
		<tr>
			<td><label for="product_city">What city is the product currently located in? *</label></td>
			<td><input type="text" id="product_city" name="product_city" value="<?php echo esc_attr($product_city); ?>" readonly></td>
		</tr>
		<tr>
			<td><label for="dealer">Are you a dealer?</label></td>
			<td>
				<select id="dealer" name="dealer" disabled>
					<option value="">Choose</option>
					<option value="yes" <?php selected($dealer, 'yes'); ?>>Yes</option>
					<option value="no" <?php selected($dealer, 'no'); ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="product_videos">Please provide any links to videos (YouTube or Vimeo) here:</label></td>
			<td><textarea id="product_videos" name="product_videos" readonly><?php echo esc_textarea($product_videos); ?></textarea></td>
		</tr>
		<tr>
			<td><label for="product_details">Product Details: *</label></td>
			<td><textarea id="product_details" name="product_details" readonly><?php echo esc_textarea($product_details); ?></textarea></td>
		</tr>
	 
		<tr>
			<td colspan="2"><i class="devsoul-make-product button button-primary button-large">Make Product</i></td>
		</tr>
	</table>