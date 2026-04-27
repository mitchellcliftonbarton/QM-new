<?php
global $params;
global $paged;

if (!isset($paged) || !$paged){
  $paged = 1;
} else {
  $paged = $GLOBALS['params']['paged'];
}

$context = Timber::context();
$context['site_theme'] = 'green-theme';
$context['site_bg'] = 'black-bg-theme';
$category_slug = $params['category'];
$term = new Timber\Term($category_slug);
$context['term_title'] = $term->name;
$context['archive_slug'] = '/video-series/';


$context['button_theme'] = 'neon';
$context['term'] = 'Video Series';

/*
----------------
PAGE DATA
----------------
*/

$args = array(
  'post_type' => 'video',
  'posts_per_page' => 20,
  'paged' => $paged,
  'tax_query' => [
      [
          'taxonomy' => 'video-categories',
          'field' => 'slug',
          'terms' => $category_slug
      ]
  ]
);

$context['posts'] = new Timber\PostQuery($args);

$posts_per_page = get_option( 'posts_per_page' );
$posts = new Timber\PostQuery($args);
$total_upto = ($posts_per_page * ($paged - 1)) + count($posts);
$context['total_upto'] = $total_upto;
$context['paged'] = $paged;
$context['cta_link'] = [
  'title' => 'Back to Video Series',
  'link' => '/video-series'
];

Timber::render( 'archive.twig', $context );
