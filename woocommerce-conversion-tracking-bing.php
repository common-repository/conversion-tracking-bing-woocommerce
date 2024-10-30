<?php /*
Plugin Name: Conversion Tracking for Bing in WooCommerce
Description: A plugin to add Bing/Microsoft Ads conversion tracking code to WooCommerce.
Version: 1.0.7.2
Author: Worcester Web Studio
Author URI: https://www.worcesterwebstudio.com/
WC requires at least: 3.7
WC tested up to: 5.7
*/

function wwsctbw_bing_tracking_menu_item() {
	add_menu_page('Bing Conversion Tracking', 'Bing Conversion Tracking', 'manage_options', 'bing_tracking', 'wwsctbw_bing_tracking_options');
}
add_action('admin_menu', 'wwsctbw_bing_tracking_menu_item');

function wwsctbw_bing_tracking_options() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	if(isset($_POST['uet_tracking_code']) && isset($_POST['currency_selector']) ) {
		check_admin_referer('bing_tracking_update_settings');
		// ID & CURRENCY
		// id is done
		// currencies set to GBP, EUR, USD
		update_option('bing_uet_tracking_code', sanitize_text_field($_POST['uet_tracking_code']));
		update_option('bing_tracking_currency', sanitize_text_field($_POST['currency_selector']));
	?>
		<div class="updated"><p><strong><?php _e('Settings saved.', 'role_signup'); ?></strong></p></div>	
	<?php } ?>
	<h1>Bing Tracking Options</h1>
	<h2>Help</h2>
	<ol>
		<li>Sign in to or register for a Microsoft/Bing Ads account <a href="https://ads.microsoft.com/" target="_blank"> here.</a></li>
		<li>In the left side menu of your Microsoft/Bing Ads account, navigate to Conversion Tracking (1) → UET Tags (2) → Create UET Tag (3). Please see an example of this <a target="_blank" href="<?php echo plugins_url('/assets/Step-1.jpg', __FILE__); ?>">here.</a></li>
		<li>Enter UET Tag Name (4) and enter a UET Tag Description (5). Any name/description will do so long as it helps you identify your account/code. Please see an example of this <a target="_blank" href="<?php echo plugins_url('/assets/Step-2.jpg', __FILE__); ?>">here.</a></li>
		<li>A Pop-up will appear with an ID and Javascript code when you save your information. You need to copy the 8 digit ID and then paste this into the UET Tracking Code field below. Please see an example of this <a target="_blank" href="<?php echo plugins_url('/assets/Step-3.jpg', __FILE__); ?>">here.</a></li>
	</ol>
	<h2>Settings</h2>
	<form method="post" action="">
		<p><strong>Enter your Microsoft/Bing Ads UET code</strong></p>
		<p>UET Tracking Code <input type="number" name="uet_tracking_code" value="<?php echo get_option('bing_uet_tracking_code'); ?>"></p>
		<p><strong>Enter your 3-letter currency code</strong></p>
		<p>Currency Code<input type="text" name="currency_selector" value="<?php echo get_option('bing_tracking_currency'); ?>"></p>
		<p>If you're not sure what your currency code is, there are some common currency codes listed at the bottom of this page.</p>
		<p>If you like this plugin, please leave us a review <a href="https://wordpress.org/plugins/conversion-tracking-bing-woocommerce/" target="_blank">here.</a></p>
		<?php wp_nonce_field('bing_tracking_update_settings'); ?>
		<?php submit_button(); ?>
	</form> 

	<h2>List of Currency Codes</h2>
	<ul>
		<li>Argentine Peso (ARS)</li>
		<li>Australian Dollar (AUD)</li>
		<li>Thai Baht (THB)</li>
		<li>Venezuelan Bolivar Fuerte (VEF)</li>
		<li>Brazilian Real (BRL)</li>
		<li>Canadian Dollar (CAD)</li>
		<li>Chilean Peso (CLP)</li>
		<li>Colombian Peso (COP)</li>
		<li>Danish Krone (DKK)</li>
		<li>European Union Euro (EUR)</li>
		<li>Hong Kong Dollar (HKD)</li>
		<li>Indian Rupee (INR)</li>
		<li>Malaysian Ringgit (MYR)</li>
		<li>Mexican Peso (MXN)</li>
		<li>New Taiwan Dollar (TWD)</li>
		<li>New Zealand Dollar (NZD)</li>
		<li>Norwegian Krone (NOK)</li>
		<li>Peruvian Nuevo Sol (PEN)</li>
		<li>Philippine Peso (PHP)</li>
		<li>Indonesian Rupiah (IDR)</li>
		<li>Singapore Dollar (SGD)</li>
		<li>Swedish Krona (SEK)</li>
		<li>Swiss Franc (CHF)</li>
		<li>Pound Sterling (GBP)</li>
		<li>US Dollar (USD)</li>

</ul>

<?php
if ( 
  in_array( 
    'woocommerce/woocommerce.php', 
    apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
  ) 
) {
}

else {
	?><h2>Please Note:</h2><h4>WooCommerce is not currently active - the plugin will not work until WooCommerce has been activated on this installation</h4><?php
}
}


if ( 
  in_array( 
    'woocommerce/woocommerce.php', 
    apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
  ) 
) {
/**
 * Add custom tracking code to the thank-you page
 */
add_action( 'woocommerce_thankyou', 'wwsctbw_my_custom_tracking' );

function wwsctbw_my_custom_tracking( $order_id ) {

	// Lets grab the order
	$order = wc_get_order( $order_id );

	/**
	 * Put your tracking code here
	 * You can get the order total etc e.g. $order->get_total();
	 */
	 
	// This is the order total
	$order->get_total();
 
	// This is how to grab line items from the order 
	$line_items = $order->get_items();

	// This loops over line items
	foreach ( $line_items as $item ) {
  		// This will be a product
  		$product = $order->get_product_from_item( $item );
  
  		// This is the products SKU
		$sku = $product->get_sku();
		
		// This is the qty purchased
		$qty = $item['qty'];
		
		// Line item total cost including taxes and rounded
		$total = $order->get_line_total( $item, true, true );
		
		// Line item subtotal (before discounts)
		$subtotal = $order->get_line_subtotal( $item, true, true );
    }
    
    // bing tracking code:
    ?>

<script>
(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"<?php echo (get_option('bing_uet_tracking_code')); ?>"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");
window.uetq = window.uetq || [];
window.uetq.push('event', 'purchase', {'event_category': 'sale', 'event_value': '<?php echo $total; ?>', 'revenue_value': '<?php echo $total; ?>', 'currency': '<?php echo (get_option('bing_tracking_currency')); ?>'});
</script>

<?php 
}

function wwsctbw_endpoint_order_recieved() {
if(!is_wc_endpoint_url('order-received')) {
			?>
			<script>
(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"<?php echo (get_option('bing_uet_tracking_code')); ?>"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");
window.uetq = window.uetq || [];
</script>
			<?php
		} 
}
add_action('wp_head', 'wwsctbw_endpoint_order_recieved');
}