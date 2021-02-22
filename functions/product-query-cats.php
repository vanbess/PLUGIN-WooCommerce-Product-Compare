<?php

/** Queries and returns products which match any of the current product's categories */
function sbwc_pc_prod_cat_query($current_id)
{
    // get current product ratings etc
    $curr_prod_data = wc_get_product($current_id);
    $curr_price = $curr_prod_data->get_price_html();
    $curr_rating_count = $curr_prod_data->get_review_count();
    $curr_avg_rating = $curr_prod_data->get_average_rating();
    $curr_img_id = $curr_prod_data->get_image_id();
    $curr_img_url = wp_get_attachment_link($curr_img_id);
    $current_prod_pc_data = maybe_unserialize(get_post_meta($current_id, 'sbwc_pc_data', true));
    $current_prod_data_count = count($current_prod_pc_data);

    $curr_prod_pc_data[$current_id] = [
        'review_count' => $curr_rating_count,
        'avg_rating' => $curr_avg_rating,
        'img' => $curr_img_url,
        'title' => get_the_title($current_id),
        'price' => $curr_price
    ];

    $main_prod_cats = $curr_prod_data->get_category_ids();

    $products = new WP_Query([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1
    ]);

    if ($products->have_posts()) :
        while ($products->have_posts()) : $products->the_post();

            $prod_data = wc_get_product(get_the_ID());
            $queried_prod_cats = $prod_data->get_category_ids();

            // check current language
            if (function_exists('pll_current_language')) :

                $current_lang = pll_current_language();
                $post_lang = pll_get_post_language(get_the_ID());
                $counter = 0;

                if ($current_lang == $post_lang && !empty(array_intersect($main_prod_cats, $queried_prod_cats))) :

                    $prod_data = wc_get_product(get_the_ID());
                    $review_count = $prod_data->get_review_count();
                    $avg_rating = $prod_data->get_average_rating();
                    $img_id = $prod_data->get_image_id();
                    $img_url = wp_get_attachment_link($img_id);
                    $prod_title = get_the_title();
                    $price = $prod_data->get_price_html();

                    // add original product data to pc data array
                    if ($counter < 1) :
                        $pc_prod_data[$current_id] = [
                            'review_count' => $curr_rating_count,
                            'avg_rating' => $curr_avg_rating,
                            'img' => $curr_img_url,
                            'title' => get_the_title($current_id),
                            'price' => $curr_price
                        ];
                    endif;

                    // add secondary product data to pc data array
                    $pc_prod_data[get_the_ID()] = [
                        'review_count' => $review_count,
                        'avg_rating' => $avg_rating,
                        'img' => $img_url,
                        'title' => $prod_title,
                        'price' => $price
                    ];
                endif;

            endif;

        endwhile;
        wp_reset_postdata();

        // display comparison chart
        if ($pc_prod_data) : $disp_counter = 0; ?>

            <div id="sbwc-pc-review-outer-cont">

                <span class="sbwc-pc-scroll-left dimmed" title="<?php pll_e('scroll left'); ?>" style="display: none;">&lsaquo;</span>
                <span class="sbwc-pc-scroll-right" title="<?php pll_e('scroll right'); ?>" style="display: none;">&rsaquo;</span>

                <?php foreach ($pc_prod_data as $prod_id => $data) :
                    if ($disp_counter < 1) : ?>
                        <div class="sbwc-pc-data">

                            <!-- title -->
                            <div id="sbwc-pc-legend-header">
                                <h3><?php pll_e('Compare To Similar Styles'); ?></h3>
                            </div>

                            <!-- spacer -->
                            <div id="sbwc-pc-legend-spacer"></div>

                            <!-- comparison data titles -->
                            <?php
                            $comp_data_arr = maybe_unserialize(get_post_meta($current_id, 'sbwc_pc_data', true));

                            if ($comp_data_arr) :
                                foreach ($comp_data_arr as $title => $content) : ?>
                                    <span class="sbwc-pc-content sbwc-pc-legend">
                                        <b><?php echo ucwords($title); ?></b>
                                    </span>
                                <?php
                                endforeach;
                            else :
                                for ($i = 0; $i < $current_prod_data_count; $i++) : ?>
                                    <span class="sbwc-pc-content sbwc-pc-legend">-</span>
                            <?php endfor;
                            endif;
                            ?>

                        </div>
                    <?php endif; ?>

                    <div class="sbwc-pc-data">

                        <!-- img -->
                        <div class="sbwc-pc-data-act sbwc-pc-img">
                            <?php echo $data['img']; ?>
                        </div>

                        <!-- title -->
                        <div class="sbwc-pc-data-act sbwc-pc-title">
                            <a href="<?php echo get_permalink($prod_id); ?>" target="_blank" title="<?php pll_e('view'); ?>">
                                <?php echo $data['title']; ?>
                            </a>
                        </div>

                        <!-- price -->
                        <div class="sbwc-pc-data-act sbwc-pc-price">
                            <?php echo $data['price']; ?>
                        </div>

                        <!-- rating -->
                        <div class="sbwc-pc-data-act sbwc-pc-rating">

                            <div class="stars-outer">
                                <div class="stars-inner" style="width: <?php echo $data['avg_rating'] * 2 * 10; ?>%"></div>
                            </div>
                            <span class="sbwc-pc-review-count">(<?php echo $data['review_count']; ?>)</span>

                        </div>

                        <!-- comparison data -->
                        <?php
                        $comp_data_arr = maybe_unserialize(get_post_meta($prod_id, 'sbwc_pc_data', true));

                        if ($comp_data_arr) :

                            foreach ($comp_data_arr as $title => $content) : ?>
                                <span class="sbwc-pc-content"><?php echo $content; ?></span>

                            <?php
                            endforeach;
                        else :
                            for ($i = 0; $i < $current_prod_data_count; $i++) : ?>
                                <span class="sbwc-pc-content">-</span>
                        <?php endfor;
                        endif;
                        ?>

                        <!-- add to cart -->
                        <div class="sbwc-pc-data-act sbwc-pc-atc">
                            <button class="sbwc-pc-add-to-cart" prod-id="<?php echo $prod_id; ?>"><?php echo pll_e('Add To Cart'); ?></button>
                        </div>
                    </div>
                <?php $disp_counter++;
                endforeach; ?>
            </div>

            <script>
                jQuery(document).ready(function($) {

                    // add to cart
                    $('button.sbwc-pc-add-to-cart').click(function(e) {
                        e.preventDefault();

                        var prod_id = $(this).attr('prod-id');

                        var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
                        var data = {
                            'action': 'sbwc_pc_atc',
                            'prod_id': prod_id
                        };
                        $.post(ajax_url, data, function(response) {
                           if (response == 'added to cart') {
                              $(document.body).trigger('wc_fragment_refresh');
                           }
                        });

                    });
                });
            </script>
<?php endif;
    endif;
}
