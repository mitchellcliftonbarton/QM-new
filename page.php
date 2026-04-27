<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post = new Timber\Post();
$context['post'] = $timber_post;

$parent_post_id = wp_get_post_parent_id($timber_post->ID);
$parent_post = new Timber\Post($parent_post_id);

if ($parent_post->title == 'Visit') {
  $context['site_theme'] = 'brown-theme';
}

if ($parent_post) {
  $sublinks = [
    'title' => $parent_post->title,
    'type' => 'static'
  ];

  $context['sublinks'] = $sublinks;
  $context['should_breadcrumb'] = true;
}

Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
