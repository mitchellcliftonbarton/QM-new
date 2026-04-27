<?php

// Exhibitions function
function create_exhibitions() {
  register_post_type( 'exhibition',
    array(
      'labels' => array(
        'name' => __( 'Exhibitions' ),
        'singular_name' => __( 'Exhibition' )
      ),
      'public' => true,
			'has_archive' => true,
			'show_in_rest' => true,
			'supports' => array('editor', 'title')
    )
  );
}

// Events function
function create_events() {
  register_post_type( 'event',
    array(
      'labels' => array(
        'name' => __( 'Events' ),
        'singular_name' => __( 'Event' )
      ),
      'public' => true,
      'has_archives' => true,
			'has_archive' => true,
			'supports' => array('title')
    )
  );
}

// Events categories
function create_events_taxonomy() {
	$labels = array(
    'name' => _x( 'Event Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Event Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Event Categories' ),
    'all_items' => __( 'All Event Categories' ),
    'parent_item' => __( 'Parent Event Category' ),
    'parent_item_colon' => __( 'Parent Event Category:' ),
    'edit_item' => __( 'Edit Event Category' ), 
    'update_item' => __( 'Update Event Category' ),
    'add_new_item' => __( 'Add New Event Category' ),
    'new_item_name' => __( 'New Event Category Name' ),
    'menu_name' => __( 'Event Categories' ),
  );    
 
  register_taxonomy('event-categories',array('event'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'event-categories' ),
  ));
}

// Staff member function
function create_staff_members() {
  register_post_type( 'staff-member',
    array(
      'labels' => array(
        'name' => __( 'Staff Members' ),
        'singular_name' => __( 'Staff Member' )
      ),
      'public' => true,
      'has_archives' => true,
			'has_archive' => true,
			'supports' => array('title')
    )
  );
}

// staff member categories
function create_staff_taxonomy() {
	$labels = array(
    'name' => _x( 'Staff Member Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Staff Member Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Staff Member Categories' ),
    'all_items' => __( 'All Staff Member Categories' ),
    'parent_item' => __( 'Parent Staff Member Category' ),
    'parent_item_colon' => __( 'Parent Staff Member Category:' ),
    'edit_item' => __( 'Edit Staff Member Category' ), 
    'update_item' => __( 'Update Staff Member Category' ),
    'add_new_item' => __( 'Add New Staff Member Category' ),
    'new_item_name' => __( 'New Staff Member Category Name' ),
    'menu_name' => __( 'Staff Member Categories' ),
  );    
 
  register_taxonomy('staff-categories',array('staff-member'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'staff-categories' ),
  ));
}

// Program function
function create_programs() {
  register_post_type( 'program',
    array(
      'labels' => array(
        'name' => __( 'Programs' ),
        'singular_name' => __( 'Program' )
      ),
      'public' => true,
      'has_archives' => true,
			'has_archive' => true,
			'supports' => array('title')
    )
  );
}

// program categories
function create_program_taxonomy() {
	$labels = array(
    'name' => _x( 'Program Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Program Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Program Categories' ),
    'all_items' => __( 'All Program Categories' ),
    'parent_item' => __( 'Parent Program Category' ),
    'parent_item_colon' => __( 'Parent Program Category:' ),
    'edit_item' => __( 'Edit Program Category' ), 
    'update_item' => __( 'Update Program Category' ),
    'add_new_item' => __( 'Add New Program Category' ),
    'new_item_name' => __( 'New Program Category Name' ),
    'menu_name' => __( 'Program Categories' ),
  );    
 
  register_taxonomy('program-categories',array('program'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'program-categories' ),
  ));
}

// Video function
function create_videos() {
  register_post_type( 'video',
    array(
      'labels' => array(
        'name' => __( 'Videos' ),
        'singular_name' => __( 'Video' )
      ),
      'public' => true,
      'has_archives' => true,
			'has_archive' => true,
			'supports' => array('title')
    )
  );
}

// video categories
function create_video_taxonomy() {
	$labels = array(
    'name' => _x( 'Video Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Video Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Video Categories' ),
    'all_items' => __( 'All Video Categories' ),
    'parent_item' => __( 'Parent Video Category' ),
    'parent_item_colon' => __( 'Parent Video Category:' ),
    'edit_item' => __( 'Edit Video Category' ), 
    'update_item' => __( 'Update Video Category' ),
    'add_new_item' => __( 'Add New Video Category' ),
    'new_item_name' => __( 'New Video Category Name' ),
    'menu_name' => __( 'Video Categories' ),
  );    
 
  register_taxonomy('video-categories',array('video'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'video-categories' ),
  ));
}