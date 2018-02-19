<?php
/**
 * @package   Support/Admin/Functions/Metaboxes
 * @author    Jérémy Levron <jeremylevron@19h47.fr>
 */


add_action( 'add_meta_boxes', 'support_metaboxes' );

/**
 * Register the metaboxes.
 *
 * The function below registers all the metaboxes used
 * in the ticket edit screen.
 *
 * @see  https://developer.wordpress.org/reference/functions/add_meta_box/
 * @since 1.0.0
 */
function support_metaboxes() {

	global $pagenow;

	// Ticket details
	add_meta_box(
		// id
		'support-metaboxes-details',
		__( 'Ticket Details', 'support' ),
		'support_meta_box_callback',
		'ticket',
		'side',
		'high',
		array(
			'template' => 'details'
		)
	);

}


/**
 * Metabox callback function.
 *
 * The below function is used to call the metaboxes content.
 * A template name is given to the function. If the template
 * does exist, the metabox is loaded. If not, nothing happens.
 *
 * @since  1.0.0
 *
 * @param  int   $post Post ID
 * @param  array $args Arguments passed to the callback function
 *
 * @return void
 */
function support_meta_box_callback( $post, $args ) {

	if ( ! is_array( $args ) || ! isset( $args['args']['template'] ) ) {
		_e( 'An error occurred while registering this metabox. Please contact support.', 'support' );
	}

	$template = $args['args']['template'];

	if ( ! file_exists( SUPPORT_PATH . "/includes/admin/metaboxes/$template.php" ) ) {
		_e( 'An error occured while loading this metabox. Please contact support.', 'support' );
	}

	// Include the metabox content
	include_once( SUPPORT_PATH . "/includes/admin/metaboxes/$template.php" );
}
