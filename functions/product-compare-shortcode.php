<?php

/** Register and display product comparison shortcode */

include SBWC_PC_PATH . 'functions/product-query-ids.php';
include SBWC_PC_PATH . 'functions/product-query-cats.php';

// add shortcode for display
add_shortcode('sbwc_pc', 'sbwc_pc_display');

function sbwc_pc_display($product_ids)
{
    // if product ids provided
    if (!empty($product_ids)) :

        $current_id = get_the_ID();
        sbwc_pc_prod_id_query($product_ids, $current_id);

    // else get product category if no specific product list is defined
    else :
        $current_id = get_the_ID();
        sbwc_pc_prod_cat_query($current_id);
    endif;
}

/** Add to cart ajax */
add_action('wp_ajax_sbwc_pc_atc', 'sbwc_pc_atc');
add_action('wp_ajax_nopriv_sbwc_pc_atc', 'sbwc_pc_atc');
function sbwc_pc_atc()
{
    if (isset($_POST)) :

        // get prod id
        $prod_id = $_POST['prod_id'];

        // get product data
        $prod_data = wc_get_product($prod_id);

        // get product type
        $prod_type = $prod_data->get_type();

        // if simple
        if ($prod_type == 'simple') :
            $added_to_cart = WC()->cart->add_to_cart($prod_id, 1);
        // if variable
        elseif ($prod_type == 'variable') :

            $variations = new WP_Query([
                'post_type' => 'product_variation',
                'post_status' => 'publish',
                'post_parent' => $prod_id,
                'posts_per_page' => -1,
                'order' => 'ASC'
            ]);

            if ($variations->have_posts()) :
                $var_counter = 0;
                while ($variations->have_posts()) : $variations->the_post();
                    if ($var_counter < 1) :
                        $added_to_cart = WC()->cart->add_to_cart($prod_id, 1, get_the_ID());
                    endif;
                    $var_counter++;
                endwhile;
                wp_reset_postdata();
            endif;
        endif;

        // if added to cart
        if ($added_to_cart) :
            print 'added to cart';
        endif;

    endif;
    wp_die();
}
