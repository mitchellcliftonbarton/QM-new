<?php
global $params;
global $paged;

if (!isset($paged) || !$paged){
  $paged = 1;
} else {
  $paged = $GLOBALS['params']['paged'];
}

$context = Timber::context();
$context['site_theme'] = 'forest-green-theme';
$context['button_theme'] = 'green';
$context['term'] = $GLOBALS['params']['year'];
$context['archive_slug'] = '/exhibitions/';

/*
----------------
PAGE DATA
----------------
*/
$firstDayOfYear = date('Ymd', strtotime($GLOBALS['params']['year'] . '-01-01'));
$lastDayOfYear = date('Ymd', strtotime($GLOBALS['params']['year'] . '-12-31 11:59:59 PM'));

$args = array(
  'post_type' => 'exhibition',
  'posts_per_page' => 20,
  'meta_key' => 'ending_date_new',
  'orderby' => 'meta_value',
  'order' => 'DESC',
  'paged' => $paged,
  'meta_query' => array(
    array(
      'key' => 'ending_date_new',
      'value' => $firstDayOfYear,
      'compare' => '>='
    ),
    array(
      'key' => 'ending_date_new',
      'value' => $lastDayOfYear,
      'compare' => '<='
    )
  )
);

$posts = new Timber\PostQuery($args);
$context['posts'] = $posts;

$posts_per_page = get_option( 'posts_per_page' );
$posts = new Timber\PostQuery();
$total_upto = ($posts_per_page * ($paged - 1)) + count($posts);
$context['total_upto'] = $total_upto;
$context['paged'] = $paged;

Timber::render( 'archive.twig', $context );
