<?php
/**
 * @package MtdevHumansTxt
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$options = array( 'mtdev_humans_txt_content', 'mtdev_add_head_link' );

if ( is_multisite() ) {
	foreach ( get_sites( array( 'fields' => 'ids' ) ) as $site_id ) {
		switch_to_blog( $site_id );
		foreach ( $options as $option ) {
			delete_option( $option );
		}
		restore_current_blog();
	}
} else {
	foreach ( $options as $option ) {
		delete_option( $option );
	}
}
