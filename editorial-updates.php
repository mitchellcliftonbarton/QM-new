<?php

function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'News';
    $submenu['edit.php'][5][0] = 'News';
    $submenu['edit.php'][10][0] = 'Add News Post';
    echo '';
}

function change_post_object_label() {
		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$labels->name = 'News';
		$labels->singular_name = 'News Post';
		$labels->add_new = 'Add News Post';
		$labels->add_new_item = 'Add News Post';
		$labels->edit_item = 'Edit News';
		$labels->new_item = 'News Post';
		$labels->view_item = 'View News Post';
		$labels->search_items = 'Search News';
		$labels->not_found = 'No News found';
		$labels->not_found_in_trash = 'No News found in Trash';
}


// Stuff to remove comments
function pk_remove_admin_menus() {
		remove_menu_page( 'edit-comments.php' );
}

function pk_remove_comment_support() {
		remove_post_type_support( 'post', 'comments' );
		remove_post_type_support( 'page', 'comments' );
}

function pk_remove_comments_admin_bar() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('comments');
}