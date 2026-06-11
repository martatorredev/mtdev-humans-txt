<?php
/**
 * @package MtdevHumansTxt
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$mtdev_options = array( 'mtdev_humans_txt_content', 'mtdev_add_head_link' );

if ( is_multisite() ) {
	foreach ( get_sites( array( 'fields' => 'ids' ) ) as $mtdev_site_id ) {
		switch_to_blog( $mtdev_site_id );
		foreach ( $mtdev_options as $mtdev_option ) {
			delete_option( $mtdev_option );
		}
		restore_current_blog();
	}
} else {
	foreach ( $mtdev_options as $mtdev_option ) {
		delete_option( $mtdev_option );
	}
}
