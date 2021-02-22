<?php

/** Custom product data panel for adding comparison data as needed */

// First Register the Tab by hooking into the 'woocommerce_product_data_tabs' filter
add_filter('woocommerce_product_data_tabs', 'sbwc_pc_data_tab');
function sbwc_pc_data_tab($product_data_tabs)
{
	$product_data_tabs['sbwc-pc-data-tab'] = array(
		'label' => __('Product Comparison Data', 'woocommerce'),
		'target' => 'sbwc_pc_comparison_data',
	);
	return $product_data_tabs;
}

// Next provide the corresponding tab content by hooking into the 'woocommerce_product_data_panels' action hook
add_action('woocommerce_product_data_panels', 'sbwc_pc_data_input');
function sbwc_pc_data_input()
{
	global $woocommerce, $post;
?>
	<!-- id below must match target registered in above add_my_custom_product_data_tab function -->
	<div id="sbwc_pc_comparison_data" class="panel woocommerce_options_panel">

		<?php if (get_post_meta($post->ID, 'sbwc_pc_data', true)) :

			$pc_data = maybe_unserialize(get_post_meta($post->ID, 'sbwc_pc_data', true));
			foreach ($pc_data as $title => $content) : ?>

				<div class="sbwc-pc-input-outer-cont">
					<!-- data title -->
					<div id="sbwc_pc_data_title_cont" class="sbwc-pc-input-cont">
						<label for="sbwc_pc_data_title"><?php pll_e('Data title'); ?></label>
						<input type="text" name="sbwc_pc_data_title[]" class="sbwc_pc_data_title" value="<?php echo $title; ?>">
					</div>

					<!-- data content -->
					<div id="sbwc_pc_data_content_cont" class="sbwc-pc-input-cont">
						<label for="sbwc_pc_data_content"><?php pll_e('Data content'); ?></label>
						<input type="text" name="sbwc_pc_data_content[]" class="sbwc_pc_data_content" value="<?php echo $content; ?>">

						<!-- add/remove -->
						<div class="sbwc-pc-add-rem-cont">
							<button class="sbwc-pc-add-data" title="<?php echo pll_e('Add'); ?>">+</button>
							<button class="sbwc-pc-rem-data" title="<?php echo pll_e('Remove'); ?>">-</button>
						</div>
					</div>
				</div>
			<?php endforeach;
		else : ?>

			<div class="sbwc-pc-input-outer-cont">
				<!-- data title -->
				<div id="sbwc_pc_data_title_cont" class="sbwc-pc-input-cont">
					<label for="sbwc_pc_data_title"><?php pll_e('Data title'); ?></label>
					<input type="text" name="sbwc_pc_data_title[]" class="sbwc_pc_data_title">
				</div>

				<!-- data content -->
				<div id="sbwc_pc_data_content_cont" class="sbwc-pc-input-cont">
					<label for="sbwc_pc_data_content"><?php pll_e('Data content'); ?></label>
					<input type="text" name="sbwc_pc_data_content[]" class="sbwc_pc_data_content">

					<!-- add/remove -->
					<div class="sbwc-pc-add-rem-cont">
						<button class="sbwc-pc-add-data" title="<?php echo pll_e('Add'); ?>">+</button>
						<button class="sbwc-pc-rem-data" title="<?php echo pll_e('Remove'); ?>">-</button>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<button id="sbwc-pc-save-data"><?php echo pll_e('Save Comparison Data'); ?></button>

	</div>
<?php
}

// css and js
add_action('admin_enqueue_scripts', 'sbwc_pc_scripts');
function sbwc_pc_scripts()
{
	wp_enqueue_style('sbwc-pc-admin-', SBWC_PC_URL . 'assets/pc_admin.css');
	wp_enqueue_script('sbwc-pc-admin-', SBWC_PC_URL . 'assets/pc_admin.js', ['jquery']);
}

// ajax
add_action('wp_ajax_sbwc_pc_save_pc_info', 'sbwc_pc_save_pc_info');
add_action('wp_ajax_nopriv_sbwc_pc_save_pc_info', 'sbwc_pc_save_pc_info');
function sbwc_pc_save_pc_info()
{
	if (isset($_POST['titles'])) :

		$titles = $_POST['titles'];
		$content = $_POST['content'];
		$prod_id = $_POST['prod_id'];

		$combined = array_combine($titles, $content);

		$pc_data_saved = update_post_meta($prod_id, 'sbwc_pc_data', maybe_serialize($combined));

		if ($pc_data_saved) :
			pll_e('Product comparison data saved.');
		else :
			pll_e('Data could not be saved. Please reload the page and try again.');
		endif;

	endif;
	wp_die();
}


?>