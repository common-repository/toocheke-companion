<?php
/**
 * Template part for displaying comic archive
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */
$archive_layout_options = get_option('toocheke-comics-archive');
$comic_archive_option = isset($archive_layout_options['layout_type']) ? $archive_layout_options['layout_type'] : 'thumbnail-list';
$templates = new Toocheke_Companion_Template_Loader;
$series_id = get_query_var('series_id');

if ($series_id) {
    set_query_var('series_id', $series_id);
}
switch ($comic_archive_option) {
    case 'thumbnail-list':
        $templates->get_template_part('content', 'comicarchivethumbnail');
        break;
    case 'plain-text-list':
        $templates->get_template_part('content', 'comicarchivetext');
        break;
    case 'calendar':
        $templates->get_template_part('content', 'comicarchivecalendar');
        break;
    case 'gallery':
        $templates->get_template_part('content', 'comicarchivegallery');
        break;

    case 'chapters-plain-text-list':
        $templates->get_template_part('content', 'comicarchivechapterstext');
        break;
    case 'chapters-gallery':
        $templates->get_template_part('content', 'comicarchivechaptersgallery');
        break;
    case 'series-plain-text-list':
        $templates->get_template_part('content', 'comicarchiveseriestext');
        break;
    case 'series-gallery':
        $templates->get_template_part('content', 'comicarchiveseriesgallery');
        break;
    case 'yearly-plain-text-list':
        $templates->get_template_part('content', 'comicarchiveyearlytext');
        break;
    case 'yearly-gallery':
        $templates->get_template_part('content', 'comicarchiveyearlygallery');
        break;
}
