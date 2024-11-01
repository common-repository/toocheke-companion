<?php
/**
 * Template part for text list archive of comics
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */
$comic_order = get_option('toocheke-comics-order') ? get_option('toocheke-comics-order') : 'DESC';
$templates = new Toocheke_Companion_Template_Loader;
?>
<?php if (have_posts()): ?>
     <header class="page-header">
            <?php
the_archive_title('<h1 class="page-title">', '</h1>');

?>
      </header><!-- .page-header -->
      <hr/>
      <?php
//for each chapter, show all posts
$series_args = array(
    'post_type' => 'series',
    'nopaging' => true,
    'orderby' => 'post_date',
    'order' => 'ASC',
    'post_status' => 'publish',
);
$series_query = new WP_Query($series_args);
if ($series_query->have_posts()):

    while ($series_query->have_posts()): $series_query->the_post();
        $series_id = get_the_ID();
        $series_name = get_the_title();
        $args = array(
            'post_type' => 'comic',
            'order' => $comic_order,
            'nopaging' => true,
            'post_parent' => $series_id,
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );
        $series_comics = get_posts($args);
        if ($series_comics) {
            echo '<h3>' . wp_kses_data($series_name) . '</h3> ';
            echo '<div id="comic-grid">';
            foreach ($series_comics as $comic) {
                setup_postdata($comic);
                echo '<span class="comic-thumbnail-wrapper">';
                if (get_the_post_thumbnail($comic->ID) != '') {

                    echo '<a href="';
                    echo esc_url(get_permalink($comic->ID));
                    echo '">';
                    echo get_the_post_thumbnail($comic->ID, 'thumbnail');
                    echo '</a>';

                } else {

                    echo '<a href="';
                    echo esc_url(get_permalink($comic->ID));
                    echo '" >';
                    echo '<img src="';
                    echo esc_attr(toocheke_catch_that_image_alt($comic));
                    echo '" alt="" />';
                    echo '</a>';

                }
                echo '<br/>';
                echo '<span class="posted-on">Posted on <a href="' . esc_url(get_permalink($comic->ID)) . '">' . wp_kses_data(date('F j, Y', strtotime($comic->post_date))) . '</a></span>';
                echo '</span>';
            } // foreach($series_comics
            echo '</div>';
        } // if ($series_comics
        ?>
			  <p>&nbsp;</p>
			  <?php
    endwhile;
    $series_query = null;
    wp_reset_postdata();
endif;
?>

<?php

else:

    $templates->get_template_part('content', 'none');

endif;
?>