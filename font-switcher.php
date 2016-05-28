<?php

/**
 * Plugin Name: Font Switcher
 * Plugin URI: http://sadler-jerome.fr
 * Description: Switch font on front
 * Version: 1.0.0
 * Author: Sadler Jérôme
 * Author URI: http://sadler-jerome.fr
 * Text Domain: font-switcher
 * Domain Path: /languages
 * Requires at least: 4.1
 * Tested up to: 4.5.2
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package         Font Switcher
 * @author          G3ronim0 <jerome@sadler-jerome.fr>
 * @copyright       Copyright (c) 2016 G3ronim0
 * @license         http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 * Loads the translation files.
 *
 * @since  1.0.0
 */
add_action( 'plugins_loaded', 'font_switcher_i18n', 2 );
function font_switcher_i18n() {
	load_plugin_textdomain( 'font-switcher', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ). 'languages' );
}

/**
 * Register style sheet
 *
 * @since 1.0.0
 */
add_action( 'wp_enqueue_scripts', 'font_switcher_styles' );
function font_switcher_styles() {

	if( $_SESSION['post-font'] == 'open-dyslexic' ) {
		wp_register_style( 'font-switcher', plugins_url( 'font-switcher/assets/css/font-switcher.css' ) );
		wp_enqueue_style( 'font-switcher' );
	}

}

/**
 * Add specific CSS class by filter
 *
 * @since 1.0.0
 */
add_filter( 'body_class', 'font_switcher_body_class' );
function font_switcher_body_class( $classes ) {
	if ( $_SESSION['post-font'] != 'font-default' ) {
		$classes[] = $_SESSION['post-font'];
	}
	return $classes;
}

/**
 * Save font value
 *
 * @since 1.0.0
 */
add_action( 'init', 'font_switcher_session' );
function font_switcher_session() {

    if( ! session_id() )
        session_start();

    if( isset( $_POST[ 'post-font' ] ) ) {
        $_SESSION[ 'post-font' ] = $_POST[ 'post-font' ];
    }

    if( ! isset( $_SESSION[ 'post-font' ] ) )
        $_SESSION[ 'post-font' ] = 'font-default';
        
}

/**
 * Form Switcher
 *
 * @since 1.0.0
 */
function switcher_session() {

    $current_font = $_SESSION[ 'post-font' ];
    ?>
    <form method="post" class="switcher">
        <p><label for="post-font"><?php _e('Choose a font', 'font-switcher'); ?> :</label>
        <select id="post-font" name="post-font" onchange="this.form.submit()">
            <option value="font-default" <?php selected( $current_font, 'font-default' ); ?>>Default</option>
            <option value="open-dyslexic" <?php selected( $current_font, 'open-dyslexic' ); ?>>Open Dyslexic</option>
        </select></p>

    </form>
    <?php
}

/**
 * Create and register Widget
 *
 * @since 1.0.0
 */
add_action( 'widgets_init', function(){
	register_widget( 'Font_Switcher_Widget' );
});

class Font_Switcher_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'font_switcher',
			'description' => 'Font Switcher',
		);
		parent::__construct( 'font_switcher', 'Font Switcher', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo switcher_session();
	}

}

