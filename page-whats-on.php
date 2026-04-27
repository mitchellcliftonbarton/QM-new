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
$context['site_theme'] = 'forest-green-theme';

function compare_order($a, $b) {
  $order_a = get_field('order_priority', $a);
  $order_b = get_field('order_priority', $b);

  if ($order_a == $order_b) {
    return 0;
  }
  return ($order_a < $order_b) ? -1 : 1;
}

function orderExhibitions($posts) {
  $posts_with_order = [];
  $posts_with_no_order = [];

  foreach ($posts as $post) {
    $order_value = get_field('order_priority', $post);

    if (isset($order_value) && $order_value > 0) {
      $posts_with_order[] = $post;
    } else {
      $posts_with_no_order[] = $post;
    }
  }

  usort($posts_with_order, 'compare_order');

  $all_posts = array_merge($posts_with_order, $posts_with_no_order);

  return $all_posts;
}


/*
----------------
PAGE DATA
----------------
*/

$today = date('Ymd');
// $todayForEvents = date('Y-m-d g:i a');
$now = strtotime('now');

$all_current = Timber::get_posts( array(
  'post_type' => 'exhibition',
  'posts_per_page' => -1,
  'meta_key' => 'ending_date_new',
  'orderby' => 'meta_value',
  'order' => 'DESC',
  'meta_query' => array(
    array(
      'key' => 'ending_date_new',
      'value' => $today,
      'compare' => '>='
    ),
    array(
      'key' => 'starting_date_new',
      'value' => $today,
      'compare' => '<='
    ),
    array(
      'key' => 'exhibition_type',
      'value' => 'On Long Term View',
      'compare' => '!='
    ),
    array(
      'relation' => 'OR',
      array(
        'key' => 'is_project',
        'value' => '0'
      ),
      array(
        'key' => 'is_project',
        'compare' => 'NOT EXISTS', 
      )
    )
  )
));

$ordered_current = orderExhibitions($all_current);

$all_events = Timber::get_posts( array(
  'post_type' => 'event',
  'posts_per_page' => 10,
  'meta_key' => 'starting_date_new',
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'meta_query' => array(
    array(
      'relation' => 'OR',
      array(
        'key' => 'starting_date_new',
        'value' => $today,
        'compare' => '>='
      ),
      array(
        'key' => 'event_type',
        'compare' => '=',
        'value' => 'ongoing'
      )
    ),
    array(
      'key' => 'show_on_whats_on',
      'value' => 1,
      'compare' => '='
    )
  )
));

$all_collections = Timber::get_posts( array(
  'post_type' => 'exhibition',
  'posts_per_page' => -1,
  'meta_query' => array(
    array(
        'key' => 'exhibition_type',
        'value' => 'On Long Term View',
        'compare' => '='
      )
  )
));

$ordered_collections = orderExhibitions($all_collections);

$all_projects = Timber::get_posts( array(
  'post_type' => 'exhibition',
  'posts_per_page' => 6,
  'meta_key' => 'starting_date_new',
  'orderby' => 'meta_value',
  'order' => 'DESC',
  'meta_query' => array(
    array(
      'key' => 'is_project',
      'compare' => 'EXISTS'
    ),
    array(
      'key' => 'is_project',
      'value' => '1',
      'compare' => '='
    )
  )
));

$ordered_projects = orderExhibitions($all_projects);

$all_upcoming_exhibitions = Timber::get_posts( array(
  'post_type' => 'exhibition',
  'posts_per_page' => -1,
  'meta_key' => 'starting_date_new',
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'meta_query' => array(
    array(
      'key' => 'starting_date_new',
      'value' => $today,
      'compare' => '>='
    ),
    array(
      array(
        'key' => 'exhibition_type',
        'value' => 'On Long Term View',
        'compare' => '!='
      )
    ),
    array(
      'relation' => 'OR',
      array(
        'key' => 'is_project',
        'value' => '0'
      ),
      array(
        'key' => 'is_project',
        'compare' => 'NOT EXISTS', 
      )
    )
  )
));

$ordered_upcoming_exhibitions = orderExhibitions($all_upcoming_exhibitions);

$past_exhibitions = Timber::get_posts( array(
  'post_type' => 'exhibition',
  'posts_per_page' => 6,
  'meta_key' => 'ending_date_new',
  'orderby' => 'meta_value',
  'order' => 'DESC',
  'meta_query' => array(
    array(
      'key' => 'ending_date_new',
      'value' => $today,
      'compare' => '<='
    ),
    array(
      array(
        'key' => 'exhibition_type',
        'value' => 'On Long Term View',
        'compare' => '!='
      )
    ),
    array(
      'relation' => 'OR',
      array(
        'key' => 'is_project',
        'value' => '0'
      ),
      array(
        'key' => 'is_project',
        'compare' => 'NOT EXISTS', 
      )
    )
  )
));

$context['current'] = $ordered_current;
$context['events'] = $all_events;
$context['collections'] = $ordered_collections;
$context['projects'] = $ordered_projects;
$context['upcoming_exhibitions'] = $ordered_upcoming_exhibitions;
$context['past_exhibitions'] = $past_exhibitions;

/*
----------------
SUBLINKS DEFINITIONS
----------------
*/

$sublinks = [
  'title' => 'What\'s On',
  'title_link' => '/whats-on',
  'type' => 'jumplinks',
  'items' => []
];

if ($all_current) {
  array_push($sublinks['items'], [
    'title' => 'Current Exhibitions'
  ]);
}

if ($all_events) {
  array_push($sublinks['items'], [
    'title' => 'Events'
  ]);
}

if ($all_collections) {
  array_push($sublinks['items'], [
    'title' => 'Collection'
  ]);
}

if ($all_projects) {
  array_push($sublinks['items'], [
    'title' => 'Projects'
  ]);
}

if (count($all_upcoming_exhibitions) > 0) {
  array_push($sublinks['items'], [
    'title' => 'Upcoming Exhibitions'
  ]);
}

array_push($sublinks['items'], [
  'title' => 'Past Exhibitions & Projects'
]);


$context['sublinks'] = $sublinks;

Timber::render( 'page-whats-on.twig', $context );
