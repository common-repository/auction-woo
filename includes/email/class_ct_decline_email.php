<?php

if ( ! class_exists('CT_B_I_D_Lose_Auction_Email') ) {
	class CT_B_I_D_Lose_Auction_Email extends WC_Email {


		public function __construct() {
			$this->id             = 'bid_auction_lose_email_id'; // Unique ID to Store Emails Settings
			$this->title          = __('cloud technalogies Auction lose', 'auction-woo'); // Title of email to show in Settings
			$this->customer_email = true; // Set true for customer email and false for admin email.
			$this->description    = __('This will send a email when a customer lose Auction', 'auction-woo'); // description of email
			$this->template_base  = BID_PLUGIN_DIR; // Base directory of template 
			$this->template_html  = 'includes/email-template/html/bid_decline_mail_html.php'; // HTML template path
			$this->template_plain = 'includes/template/plain/bid_decline_mail_plain.php'; // Plain template path
			$this->placeholders   = array( // Placeholders/Variables to be used in email
				'{Site_title}' => '',
				'{email_subject}' => '',
				
			);
			// Call to the  parent constructor.
			parent::__construct(); // Must call constructor of parent class
			// Trigger function  for this customer email cancelled order.
			add_action('addify_refer_share_email_notification', array( $this, 'trigger' ), 10, 1); // action hook(s) to trigger email 
		}

		// Step 3: change default subject of email by overriding the parent class method
		// Ex.
		public function get_default_subject() {
			return __('Subject for Email', 'auction-woo');
		}

		// Step 4: change default heading of email by overriding the parent class method

		public function get_default_heading() {
			return __('Heading for Email', 'auction-woo');
		}

		// Step 5: Must over ride trigger method to replace your placeholders and send email

		public function trigger( $post_id = 0 ) {
			
			$product_id         = wp_get_post_parent_id( $post_id );
			$product            = get_the_title($product_id);
			$form_data          = (array) get_post_meta($post_id, 'bpg_formdata', true);
			$user               = get_user_by('ID', isset($form_data['current_user_id']) ? $form_data['current_user_id'] : 0 );
			$email              = $user->user_email;
			$date_format        = get_option( 'date_format' );

			
			$this->setup_locale();
			$this->placeholders['{Site_title}']         =   get_option('af_rfw_email_content_multiple_review');
	
			$this->setup_locale();
			
			if ($this->is_enabled()) {

				$this->send( $email, $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
			}
			$this->restore_locale();
		}

		// Step 6: Override the get_content_html method to add your template of html email

		public function get_content_html() {
			return wc_get_template_html(
				$this->template_html,
				array(
					'member'             => $this->object,
					'email_heading'      => $this->get_heading(),
					'additional_content' => $this->get_additional_content(),
					'sent_to_admin'      => false,
					'plain_text'         => false,
					'email'              => $this,
				),
				$this->template_base,
				$this->template_base
			);
		}
		// Note: Path and default path in wc_get_template_html() can be defined as, path is defined path to over-ride email template and default path is path to your plugin template.
		// Read more about wc_get_template and wc_locate_template() to understand the over-riding templates in WooCommerce.
		// Step 7: Override the get_content_plain method to add your template of plain email
		
		public function get_content_plain() {
			return wc_get_template_html(
				$this->template_html,
				array(
					'member'              => $this->object,
					'email_heading'      => $this->get_heading(),
					'additional_content' => $this->get_additional_content(),
					'sent_to_admin'      => false,
					'plain_text'         => false,
					'email'              => $this,
				),
				$this->template_base,
				$this->template_base
			);
		}
	}
}
