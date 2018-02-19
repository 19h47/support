<?php

/**
 * Post type
 *
 * @author Jérémy Levron <jeremylevron@19h47.fr>
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
		'read'				=> 'view_ticket',
		'read_post'			=> 'view_ticket',
		'read_private_posts' 		=> 'view_private_ticket',
		'edit_post'			=> 'edit_ticket',
		'edit_posts'			=> 'edit_ticket',
		'edit_others_posts' 		=> 'edit_other_ticket',
		'edit_private_posts' 		=> 'edit_private_ticket',
		'edit_published_posts' 		=> 'edit_ticket',
		'publish_posts'			=> 'create_ticket',
		'delete_post'			=> 'delete_ticket',
		'delete_posts'			=> 'delete_ticket',
		'delete_private_posts' 	 	=> 'delete_private_ticket',
		'delete_published_posts'	=> 'delete_ticket',
		'delete_others_posts' 	 	=> 'delete_other_ticket',
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


add_action( 'init', 'support_register_post_status' );

/**
 * Register custom ticket status.
 *
 * @since  1.0.0
 * @return void
 */
function support_register_post_status() {
	$status = support_get_post_status();

	foreach ( $status as $id => $custom_status ) {
		$args = array(
			'label'                     => $custom_status,
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( "$custom_status <span class='count'>(%s)</span>", "$custom_status <span class='count'>(%s)</span>", 'support' ),
		);
		register_post_status( $id, $args );
	}

	// Hardcode the read and unread status used for replies.
	// register_post_status( 'read',
	// 	array(
	// 		'label' => _x( 'Read', 'Reply status', 'support' ),
	// 		'public' => false
	// 	)
	// );
	// register_post_status( 'unread',
	// 	array(
	// 		'label' => _x( 'Unread', 'Reply status', 'support' ),
	// 		'public' => false
	// 	)
	// );
}


/**
 * Get available ticket status.
 *
 * @since  1.0.0
 * @return array List of filtered statuses
 */
function support_get_post_status() {
	$status = array(
		'queued'     	=> _x( 'New', 'Ticket status', 'support' ),
		'processing' 	=> _x( 'In Progress', 'Ticket status', 'support' ),
		'hold'       	=> _x( 'On Hold', 'Ticket status', 'support' ),
		'test'		=> _x( 'To test', 'Ticket status', 'support' ),
		'resolved'	=> _x( 'Resolved', 'Ticket status', 'support' ),
	);

	return apply_filters( 'support_ticket_statuses', $status );
}


add_action( 'admin_footer', 'support_add_post_status' );

/**
 * Add post status
 *
 * @see  https://paulund.co.uk/register-new-post-status
 * @since  1.0.0
 */
function support_add_post_status( $post ) {

	if ( get_current_screen()->post_type !== 'ticket' ) {
		return false;
	}

	global $wp_post_statuses, $post;

	$options = '';
	$display = '';

	foreach ( $wp_post_statuses as $status ) {

		if ( $status->_builtin ) {
			continue;
		}

		// Match against the current posts status
		$selected = selected( $post->post_status, $status->name, false );

		// If we one of our custom post status is selected, remember it
		$selected AND $display = $status->label;

		// Build the options
		$options .= "<option{$selected} value='{$status->name}'>{$status->label}</option>";
	}
	
	// @todo put this script in partial
	?><script>
		jQuery(document).ready(function($) {

			<?php
		
			// Add the selected post status label to the "Status: [Name] (Edit)"
			if ( ! empty( $display ) ) : ?>
				$('#post-status-display').html('<?php echo $display ?>');
			<?php endif


	    		// Add the options to the <select> element
			?>
			$('.edit-post-status').on( 'click', function() {
				var select = $('#post-status-select').find('select');

				$(select).append('<?php echo $options ?>');
			});
		});
	</script>
	<?php
}
