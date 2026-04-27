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
$context['site_theme'] = 'brown-theme';

/*
----------------
SUBLINKS DEFINITIONS
----------------
*/

$sublinks = [
  'title' => 'Visit',
  'title_link' => '/visit',
  'type' => 'jumplinks',
  'items' => []
];

$sections = get_field('sections', $timber_post);
$has_sections = count($sections) > 0;

if ($has_sections) {
  foreach ($sections as $item) {
    array_push($sublinks['items'], [
      'title' => $item['slug']
    ]);
  }
}

$context['sublinks'] = $sublinks;

Timber::render( 'page-visit.twig', $context );
