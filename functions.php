<?php

function get_remote_part( $url, $minutes_to_save = 60 ) {
	$transient_name = 'get_remote_part_' . substr( md5( $url ), 16 );
	if ( false === ( $value = get_transient( $transient_name ) ) ) {
		$value = wp_remote_retrieve_body( wp_remote_get( $url ) );
		if( $value ) {
			set_transient( $transient_name, $value, ( 60 * $minutes_to_save ) );
		}
	}
	return $value;
}

function get_wporg_header( $minutes_to_save = 60 ) {
	$url = 'https://wordpress.org/header.php';
	return get_remote_part( $url, $minutes_to_save );
}

function the_wporg_header_top_part() {
	$wporg_header = get_wporg_header();
	$the_wporg_header_top_part = substr( $wporg_header, 0, strpos( $wporg_header, '</head>' ) );
	echo apply_filters( 'the_wporg_header_top_part', $the_wporg_header_top_part );
}

function the_wporg_header_bottom_part() {
	$wporg_header = get_wporg_header();
	$the_wporg_header_bottom_part = substr( $wporg_header, strpos( $wporg_header, '</head>' ) );
	echo apply_filters( 'the_wporg_header_bottom_part', $the_wporg_header_bottom_part );
}

add_filter( 'the_wporg_header_top_part', 'wporg_add_page_title' );
function wporg_add_page_title( $the_wporg_header_top_part ) {
	$wp_title = get_bloginfo( 'name' ) . ' | ' . ( is_home() ? get_bloginfo( 'description' ) : wp_title( '', false ) );
	return str_replace( '<title>WordPress</title>', "<title>{$wp_title}</title>", $the_wporg_header_top_part );
}

add_filter( 'the_wporg_header_bottom_part', 'wporg_add_body_class' );
function wporg_add_body_class( $the_wporg_header_bottom_part ) {
	$body_class = ' class="' . implode( ' ', get_body_class() ) . '" ';
	return str_replace( '<body ', "<body{$body_class}", $the_wporg_header_bottom_part );
}

function get_wporg_footer( $minutes_to_save = 60 ) {
	$url = 'https://wordpress.org/footer.php';
	return get_remote_part( $url, $minutes_to_save );
}

function the_wporg_footer_top_part() {
	$wporg_footer = get_wporg_footer();
	$the_wporg_footer_top_part = substr( $wporg_footer, 0, strpos( $wporg_footer, '</body>' ) );
	echo apply_filters( 'the_wporg_footer_top_part', $the_wporg_footer_top_part );
}

function the_wporg_footer_bottom_part() {
	$wporg_footer = get_wporg_footer();
	$the_wporg_footer_bottom_part = substr( $wporg_footer, strpos( $wporg_footer, '</body>' ) );
	echo apply_filters( 'the_wporg_footer_bottom_part', $the_wporg_footer_bottom_part );
}
