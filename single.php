<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();
$timber_post = Timber::query_post();
$context['bg'] = 'black-theme';

$context['post'] = $timber_post;
$post_type = $timber_post->post_type;
$today = strtotime("now");

if ($post_type == 'exhibition') {
	$context['site_theme'] = 'forest-green-theme';

	$sublinks = [
		'title' => 'Exhibition',
		'title_link' => '/exhibitions/past',
		'type' => 'static'
	];

	$context['sublinks'] = $sublinks;

	// $event_dates = get_field('calendar_event_dates', $timber_post);
	// $start_field = $event_dates['starting_date'];
	// $end_field = $event_dates['ending_date'];

	// echo $end_field;
} else if ($post_type == 'event') {
	$context['site_theme'] = 'forest-green-theme';

	$sublinks = [
		'title' => 'Calendar',
		'title_link' => '/calendar',
		'type' => 'static'
	];

	$context['sublinks'] = $sublinks;

	$end_date = strtotime(get_field('ending_date_new', $timber_post));

	if ($today > $end_date) {
		$context['is_past'] = true;
	} else {
		$context['is_past'] = false;
	}

	$start_date_field = strtotime(get_field('starting_date_new', $timber_post));
	$start_string = date('F j, Y', $start_date_field);

	$start_time_field = get_field('starting_time_new', $timber_post);
	$start_string = $start_string . ' ' . $start_time_field;

	// $date = date('F j, Y g:i a', strtotime($start_string));
	// $end_date = date('F j, Y g:i a', strtotime($start_string . ' +2 hour'));
	$date = strtotime($start_string);
	$end_date = strtotime($start_string . ' +2 hour');

} else if ($post_type == 'program') {
	$is_learn = get_field('is_learn', $timber_post);
	if ($is_learn == 1) {
		$context['site_theme'] = 'pink-theme';
	} else {
		$context['site_theme'] = 'grey-theme';
	}

	$sublinks = [
		'title' => 'Program',
		'title_link' => '/programs/archive',
		'type' => 'static'
	];

	$context['sublinks'] = $sublinks;
} else if ($post_type == 'staff-member') {
	if (get_field('disable_detail', $timber_post->ID)) {
		$cat = $timber_post->terms('staff-categories')[0]->slug ?? 'board';
		wp_redirect('/board-staff/' . $cat, 301);
		exit;
	}

	$context['site_theme'] = 'purple-theme';

	$sublinks = [
		'title' => 'Board and Staff',
		'title_link' => '/board-staff',
		'type' => 'static'
	];

	$context['sublinks'] = $sublinks;

	$cat = $timber_post->terms('staff-categories')[0]->slug;
	$all_staff_in_category = Timber::get_posts(array(
		'post_type' => 'staff-member',
		'posts_per_page' => -1,
		'tax_query' => [
				[
						'taxonomy' => 'staff-categories',
						'field' => 'slug',
						'terms' => $cat
				]
		]
	));

	$nextLink = null;

	$currentIndex = array_search($timber_post->ID, array_column($all_staff_in_category, 'ID'));
	$length = count($all_staff_in_category);

	if ($currentIndex + 1 == $length) {
		$nextLink = $all_staff_in_category[0];
	} else {
		$nextLink = $all_staff_in_category[$currentIndex + 1];
	}

	$context['next_link'] = $nextLink;
} else if ($post_type == 'video') {
	$context['site_theme'] = 'green-theme';
	$context['site_bg'] = 'black-bg-theme';

	$sublinks = [
		'title' => 'Video',
		'title_link' => '/video-series',
		'type' => 'static'
	];

	$context['sublinks'] = $sublinks;

	$cat = $timber_post->terms('video-categories')[0]->slug;
	$all_video_in_category = Timber::get_posts(array(
		'post_type' => 'video',
		'posts_per_page' => 4,
		'tax_query' => [
				[
						'taxonomy' => 'video-categories',
						'field' => 'slug',
						'terms' => $cat
				]
		]
	));

	$context['videos_in_category'] = $all_video_in_category;
} else if ($post_type == 'post') {
	$context['site_theme'] = 'green-theme';

	$sublinks = [
		'title' => 'News',
		'title_link' => '/news',
		'type' => 'static'
	];

	$context['sublinks'] = $sublinks;

	$all_posts = Timber::get_posts(array(
		'post_type' => 'post',
		'posts_per_page' => -1
	));

	$nextLink = null;

	$currentIndex = array_search($timber_post->ID, array_column($all_posts, 'ID'));
	$length = count($all_posts);

	if ($currentIndex + 1 == $length) {
		$nextLink = $all_posts[0];
	} else {
		$nextLink = $all_posts[$currentIndex + 1];
	}

	$context['next_link'] = $nextLink;
}

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single.twig' ), $context );
}
