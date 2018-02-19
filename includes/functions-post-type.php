<?php

/**
 * Post type
 *
 * @author Jérémy Levron jeremylevron@19h47.fr
 */


add_action( 'init', 'support_register_post_type', 10, 0 );

/**
 * Register the ticket post type
 *
 * @since 1.0.0
 */
function support_register_post_type() {

	$slug = defined( 'SUPPORT_SLUG' ) ? sanitize_title( SUPPORT_SLUG ) : 'ticket';


	// Supported components
	$supports = array( 'title' );


	// Template components for Gutenberg
	// $gutenburg_new_template = array(
	// 	array(
	// 		'core/paragraph',
	// 		array(
	// 			'placeholder' => _x('Enter the contents for your new ticket here', 'placeholder for main paragraph when adding a new ticket', 'support' )
	// 		)
	// 	),
	// );


	// If the post is being created we add the editor
	if( ! isset( $_GET['post'] ) ) {
		array_push( $supports, 'editor' );
	}


	// Post type labels
	$labels =  array(
		'name'               => _x( 'Tickets', 'post type general name', 'support' ),
		'singular_name'      => _x( 'Ticket', 'post type singular name', 'support' ),
		'menu_name'          => _x( 'Tickets', 'admin menu', 'support' ),
		'name_admin_bar'     => _x( 'Ticket', 'add new on admin bar', 'support' ),
		'add_new'            => _x( 'Add New', 'ticket', 'support' ),
		'add_new_item'       => __( 'Add New Ticket', 'support' ),
		'new_item'           => __( 'New Ticket', 'support' ),
		'edit_item'          => __( 'Edit Ticket', 'support' ),
		'view_item'          => __( 'View Ticket', 'support' ),
		'all_items'          => __( 'All Tickets', 'support' ),
		'search_items'       => __( 'Search Tickets', 'support' ),
		'parent_item_colon'  => __( 'Parent Ticket:', 'support' ),
		'not_found'          => __( 'No tickets found.', 'support' ),
		'not_found_in_trash' => __( 'No tickets found in Trash.', 'support' ),
	);
	apply_filters( 'support_ticket_type_labels', $labels );


	// Post type rewrite
	$rewrite = array(
		'slug' 			=> apply_filters( 'support_rewrite_slug', $slug ),
		'with_front' 	=> false
	);

	// Post type capabilities
	$capabilities = array(
		'read'					 => 'view_ticket',
		'read_post'				 => 'view_ticket',
		'read_private_posts' 	 => 'view_private_ticket',
		'edit_post'				 => 'edit_ticket',
		'edit_posts'			 => 'edit_ticket',
		'edit_others_posts' 	 => 'edit_other_ticket',
		'edit_private_posts' 	 => 'edit_private_ticket',
		'edit_published_posts' 	 => 'edit_ticket',
		'publish_posts'			 => 'create_ticket',
		'delete_post'			 => 'delete_ticket',
		'delete_posts'			 => 'delete_ticket',
		'delete_private_posts' 	 => 'delete_private_ticket',
		'delete_published_posts' => 'delete_ticket',
		'delete_others_posts' 	 => 'delete_other_ticket',
	);
	apply_filters( 'support_ticket_type_capabilities', $capabilities );


	// Post type arguments
	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => $rewrite,
		// 'capability_type'     => 'view_ticket',
		// 'capabilities'        => $capabilities,
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-forms',
		'supports'            => $supports,
		// 'template' 			  => $gutenburg_new_template
	);
	apply_filters( 'support_ticket_type_args', $args );


	register_post_type( 'ticket', $args );
}
