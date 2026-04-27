<?php
global $params;
global $paged;

if (!isset($paged) || !$paged){
  $paged = 1;
} else {
  $paged = $GLOBALS['params']['paged'];
}

$context = Timber::context();
$context['site_theme'] = 'purple-theme';
$category_slug = $params['category'];
$term = new Timber\Term($category_slug);
$context['term_title'] = $term->name;

/*
----------------
SUBLINKS DEFINITIONS
----------------
*/

$sublinks = [
  'title' => 'Board and Staff',
  'title_link' => '/board-staff',
  'items' => []
];

$terms = Timber::get_terms('staff-categories');

foreach ($terms as $term) {
  array_push($sublinks['items'], [
    'title' => $term->name,
    'link' => '/board-staff/' . $term->slug,
    'active' => $term->slug == $category_slug
  ]);
}

$context['sublinks'] = $sublinks;


/*
----------------
PAGE DATA
----------------
*/

$args = array(
  'post_type' => 'staff-member',
  'posts_per_page' => -1,
  'paged' => $paged,
  'tax_query' => [
      [
          'taxonomy' => 'staff-categories',
          'field' => 'slug',
          'terms' => $category_slug
      ]
  ]
);

$context['paged'] = $paged;
$posts = new Timber\PostQuery($args);

$posts_with_order = [];
$posts_with_no_order = [];
$posts_with_nothing = [];

foreach ($posts as $post) {
  $order_value = get_field('order_indicator', $post);
  $last_name_value = get_field('last_name', $post);

  if (isset($order_value) && $order_value > 0) {
    $posts_with_order[] = $post;
  } else if (isset($last_name_value)) {
    $posts_with_no_order[] = $post;
  } else {
    $posts_with_nothing[] = $post;
  }
}

function compare_order($a, $b) {
  $order_a = get_field('order_indicator', $a);
  $order_b = get_field('order_indicator', $b);

  if ($order_a == $order_b) {
    return 0;
  }
  return ($order_a < $order_b) ? -1 : 1;
}

usort($posts_with_order, 'compare_order');

function compare_names($a, $b) {
  $last_name_a = get_field('last_name', $a);
  $last_name_b = get_field('last_name', $b);

  if ($last_name_a == $last_name_b) {
    return 0;
  }
  return strcmp($last_name_a, $last_name_b);
}

usort($posts_with_no_order, 'compare_names');

// print_r($posts_with_no_order);

$all_posts = array_merge($posts_with_order, $posts_with_no_order, $posts_with_nothing);

$context['posts'] = $all_posts;

Timber::render( 'board-staff.twig', $context );
