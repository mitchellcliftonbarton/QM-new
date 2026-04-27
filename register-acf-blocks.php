<?php

// ACF PRO Custom Block Types ---------------------------------------

function checkPost () {
    $id = get_the_ID();
    $parent_post_id = wp_get_post_parent_id($id);
    $parent_post = get_post($parent_post_id);

    if ($parent_post->post_title == 'Visit') {
        return 'brown-theme';
    }

    return 'green-theme';
}

function my_acf_init() {
    // Bail out if function doesn’t exist.
    if ( ! function_exists( 'acf_register_block' ) ) {
        return;
    }

    // Register Large Title ----------------------------------------

    acf_register_block( array(
        'name'               => 'large-title',
        'title'              => __('Large Title'),
        'render_callback' 	 => 'large_title_callback',
        'category'           => 'formatting',
        'icon'               => 'admin-comments',
        'keywords'           => array( 'page', 'row' ),
		'post_types'         => array('page', 'post'),
		'mode'               => 'edit'
    ) );

	function large_title_callback( $block, $content = '', $is_preview = false ) {
	    $context = Timber::context();
        $context['site_theme'] = checkPost();
	    $context['block'] = $block;
	    $context['fields'] = get_fields();
	    $context['is_preview'] = $is_preview;
	    Timber::render( 'templates/blocks/large-title.twig', $context );
	}

    // Register Text Section ----------------------------------------

    acf_register_block( array(
        'name'               => 'text-section',
        'title'              => __('Text Section'),
        'render_callback' 	 => 'text_section_callback',
        'category'           => 'formatting',
        'icon'               => 'admin-comments',
        'keywords'           => array( 'page', 'row' ),
		'post_types'         => array('page', 'post'),
		'mode'               => 'edit'
    ) );

	function text_section_callback( $block, $content = '', $is_preview = false ) {
        $context = Timber::context();
        $context['site_theme'] = checkPost();
	    $context['block'] = $block;
	    $context['fields'] = get_fields();
	    $context['is_preview'] = $is_preview;
	    Timber::render( 'templates/blocks/text-section.twig', $context );
	}

    // Register Posts Section ----------------------------------------

    acf_register_block( array(
        'name'               => 'posts-section',
        'title'              => __('Posts Section'),
        'render_callback' 	 => 'posts_section_callback',
        'category'           => 'formatting',
        'icon'               => 'admin-comments',
        'keywords'           => array( 'page', 'row' ),
		'post_types'         => array('page', 'post'),
		'mode'               => 'edit'
    ) );

	function posts_section_callback( $block, $content = '', $is_preview = false ) {
	    $context = Timber::context();
        $context['site_theme'] = checkPost();
	    $context['block'] = $block;
	    $context['fields'] = get_fields();
	    $context['is_preview'] = $is_preview;
	    Timber::render( 'templates/blocks/posts-section.twig', $context );
	}

    // Register CTA ----------------------------------------

    acf_register_block( array(
        'name'               => 'cta',
        'title'              => __('CTA'),
        'render_callback' 	 => 'cta_callback',
        'category'           => 'formatting',
        'icon'               => 'admin-comments',
        'keywords'           => array( 'page', 'row' ),
		'post_types'         => array('page', 'post'),
		'mode'               => 'edit'
    ) );

	function cta_callback( $block, $content = '', $is_preview = false ) {
	    $context = Timber::context();
        $context['site_theme'] = checkPost();
	    $context['block'] = $block;
	    $context['fields'] = get_fields();
	    $context['is_preview'] = $is_preview;
	    Timber::render( 'templates/blocks/cta.twig', $context );
	}

    // Register Image ----------------------------------------

    acf_register_block( array(
        'name'               => 'image',
        'title'              => __('Image'),
        'render_callback' 	 => 'image_callback',
        'category'           => 'formatting',
        'icon'               => 'admin-comments',
        'keywords'           => array( 'page', 'row' ),
		'post_types'         => array('page'),
		'mode'               => 'edit'
    ) );

	function image_callback( $block, $content = '', $is_preview = false ) {
	    $context = Timber::context();
        $context['site_theme'] = checkPost();
	    $context['block'] = $block;
	    $context['fields'] = get_fields();
	    $context['is_preview'] = $is_preview;
	    Timber::render( 'templates/blocks/image.twig', $context );
	}

    // Register Links ----------------------------------------

    acf_register_block( array(
        'name'               => 'links',
        'title'              => __('Links'),
        'render_callback' 	 => 'links_callback',
        'category'           => 'formatting',
        'icon'               => 'admin-comments',
        'keywords'           => array( 'page', 'row' ),
		'post_types'         => array('page', 'post'),
		'mode'               => 'edit'
    ) );

	function links_callback( $block, $content = '', $is_preview = false ) {
	    $context = Timber::context();
        $context['site_theme'] = checkPost();
	    $context['block'] = $block;
	    $context['fields'] = get_fields();
	    $context['is_preview'] = $is_preview;
	    Timber::render( 'templates/blocks/links.twig', $context );
	}

    // Register Text with Image ----------------------------------------

    acf_register_block( array(
        'name'               => 'text-with-images',
        'title'              => __('Text with Images'),
        'render_callback' 	 => 'text_with_images_callback',
        'category'           => 'formatting',
        'icon'               => 'admin-comments',
        'keywords'           => array( 'page', 'row' ),
		'post_types'         => array('page', 'post'),
		'mode'               => 'edit'
    ) );

	function text_with_images_callback( $block, $content = '', $is_preview = false ) {
	    $context = Timber::context();
        $context['site_theme'] = checkPost();
	    $context['block'] = $block;
	    $context['fields'] = get_fields();
	    $context['is_preview'] = $is_preview;
	    Timber::render( 'templates/blocks/text-with-images.twig', $context );
	}
}

// Limit Custom Blocks -----------------------------------

function my_plugin_allowed_block_types( $block_editor_context, $editor_context ) {
    if ( !empty( $editor_context->post ) ) {
        $type = get_post_type($editor_context->post->ID);

        if ($type == 'page') {
            return array(
                'acf/large-title',
                'acf/text-section',
                'acf/posts-section',
                'acf/cta',
                'acf/image',
                'acf/links',
                'core/paragraph'
            );
        } else if ($type == 'post') {
            return array(
                'core/paragraph',
                'core/image',
                'core/heading',
                'core/list',
                'core/quote',
                'core/shortcode',
                'core/youtube',
                'core/video',
                'core/audio',
                'core/spacer'
            );
        }
    }
 
    return $block_editor_context;
}