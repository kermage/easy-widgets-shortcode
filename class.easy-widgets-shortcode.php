<?php

/**
 * @package Easy Widgets Shortcode
 * @since 0.1.0
 */

if ( ! class_exists( 'Easy_Widgets_Shortcode' ) ) {
    class Easy_Widgets_Shortcode {
        
        private static $instance;
        
        
        public static function instance() {
            
            if ( ! isset( self::$instance ) ) {
                self::$instance = new self();
            }
            
            return self::$instance;
            
        }
        
        
        private function __construct() {
            
            
        }
        
    }
}

// Get the Easy Widgets Shortcode plugin running
Easy_Widgets_Shortcode::instance();
