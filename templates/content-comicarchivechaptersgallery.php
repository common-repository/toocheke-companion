<?php
/**
 * Template part for text list archive of comics
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */
$templates = new Toocheke_Companion_Template_Loader;
$comic_order = get_option('toocheke-comics-order') ? get_option('toocheke-comics-order') : 'DESC';
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
$chapter_args = array(
    'taxonomy' => 'chapters',
    'style' => 'none',
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => 'chapter-order',
            'type' => 'NUMERIC',
        )),
    'show_count' => 0,
);
$chapters = get_categories($chapter_args);

foreach ($chapters as $chapter) {

    $args = array(
        'post_type' => 'comic',
        'order' => $comic_order,
        'nopaging' => true,
        "tax_query" => array(
            array(
                'taxonomy' => "chapters", // use the $tax you define at the top of your script
                'field' => 'term_id',
                'terms' => $chapter->term_id, // use the current term in your foreach loop
            ),
        ),
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    );
    $chapters_posts = get_posts($args);
    if ($chapters_posts) {
        echo '<h3>' . wp_kses_data($chapter->name) . '</h3> ';
        echo '<div id="comic-grid">';
        foreach ($chapters_posts as $comic) {
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
            echo '<span class="posted-on">Posted on <a href="'. esc_url(get_permalink($comic->ID)) .'">' . wp_kses_data(date('F j, Y', strtotime($comic->post_date))) .'</a></span>';
            echo '</span>';
        } // foreach($chapters_posts
        echo '</div>';
    } // if ($chapters_posts
    ?>
	  <p>&nbsp;</p>
	  <?php
} // foreach($chapters
?>

<?php

else:

    $templates->get_template_part('content', 'none');

endif;
?>