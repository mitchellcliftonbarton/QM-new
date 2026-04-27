<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );

$context = Timber::context();
$query = get_search_query();
$context['search_query'] = $query;
$posts = new Timber\PostQuery();
$context['posts'] = $posts;
$context['bg'] = 'green-theme';
$context['paged'] = $paged;
$context['meta_title'] = 'Search for "' . $query . '"';

Timber::render( $templates, $context );