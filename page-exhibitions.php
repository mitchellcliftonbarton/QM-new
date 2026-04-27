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

$today = date('Ymd');

$all_upcoming_exhibitions = Timber::get_posts( array(
  'post_type' => 'exhibition',
  'posts_per_page' => -1,
  'meta_key' => 'opening_date',
  'orderby' => 'meta_value',
  'order' => 'DESC',
  'meta_query' => array(
    array(
      'key' => 'opening_date',
      'value' => $today,
      'compare' => '>='
    ),
    array(
      'key' => 'exhibition_type',
      'value' => 'On Long Term View',
      'compare' => '!='
    )
  )
));

$context['upcoming'] = $all_upcoming_exhibitions;

Timber::render( 'page-exhibitions.twig', $context );
