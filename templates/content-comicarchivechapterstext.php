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
        foreach ($chapters_posts as $comic) {
            setup_postdata($comic);?>
            <div class="comic-archive-item">
  <span class="comic-archive-date"><?php echo wp_kses_data(date('F j, Y', strtotime($comic->post_date))); ?></span>
  <span class="comic-archive-title"><a href="<?php echo esc_url(get_permalink($comic->ID)); ?>" title="<?php echo esc_attr($comic->post_title) ?>"><?php echo wp_kses_data($comic->post_title) ?></a></span>
  </div>

            <?php
} // foreach($chapters_posts
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