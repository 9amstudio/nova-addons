<?php
/**
 * Add more data for user
 */

/**
 * Add more contact method for user
 *
 * @param array $methods
 *
 * @return array
 */
function nova_addons_user_contact_methods( $methods ) {
	$methods['facebook']  = esc_html__( 'Facebook', 'nova' );
	$methods['twitter']   = esc_html__( 'Twitter', 'nova' );
	$methods['google']    = esc_html__( 'Google Plus', 'nova' );
	$methods['pinterest'] = esc_html__( 'Pinterest', 'nova' );
	$methods['instagram'] = esc_html__( 'Instagram', 'nova' );

	return $methods;
}

add_filter( 'user_contactmethods', 'nova_addons_user_contact_methods' );