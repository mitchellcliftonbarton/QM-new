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
$context['site_theme'] = 'green-theme';

/*
----------------
SUBLINKS DEFINITIONS
----------------
*/

$sublinks = [
  'title' => 'Magazine',
  'title_link' => '/magazine',
  'type' => 'jumplinks',
  'items' => []
];

$categories = get_field('featured_categories', $timber_post);
$has_categories = count($categories) > 0;

// print_r($categories);

$categories_array = [];

if ($has_categories) {
  foreach ($categories as $item) {

    $cat = $item['category']->slug;
    $all_posts_in_category = Timber::get_posts(array(
      'post_type' => 'post',
      'posts_per_page' => 8,
      'tax_query' => [
          [
              'taxonomy' => 'category',
              'field' => 'slug',
              'terms' => $cat
          ]
      ]
    ));

    array_push($categories_array, [
      'title' => $item['title'],
      'cat_title' => $item['category']->name,
      'posts' => $all_posts_in_category,
      'slug' => $item['category']->slug
    ]);

    array_push($sublinks['items'], [
      'title' => $item['category']->name
    ]);
  }
}

$context['sublinks'] = $sublinks;
$context['categories_array'] = $categories_array;

// print_r($categories_array);

Timber::render( 'page-magazine.twig', $context );
