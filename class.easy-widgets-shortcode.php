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
            
            add_shortcode( 'ews_sidebar', array( $this, 'sidebar_shortcode' ) );
            add_shortcode( 'ews_widget', array( $this, 'widget_shortcode' ) );
            add_action( 'in_widget_form', array( $this, 'shortcode_info' ) );
            
        }
        
        
        public function sidebar_shortcode( $atts ) {
            
            extract( shortcode_atts( array(
                'id'  => ''
            ), $atts ) );
            
            ob_start();
            dynamic_sidebar( $id );
            $output = ob_get_clean();
            
            return $output;
            
        }
        
        
        public function widget_shortcode( $atts ) {
            
            extract( shortcode_atts( array(
                'id'  => ''
            ), $atts ) );
            
            global $wp_registered_sidebars;
            global $wp_registered_widgets;
            global $_wp_sidebars_widgets;
            
            foreach ( $_wp_sidebars_widgets as $sidebar => $widgets ) {
                if ( is_array( $widgets ) ) {
                    foreach ( $widgets as $widget ) {
                        if ( $widget == $id ) { 
                            $sidebar_id = $sidebar;
                            break 2;
                        }
                    }
                }
            }
            
            $callback = $wp_registered_widgets[$id]['callback'];
            $params = apply_filters( 'dynamic_sidebar_params',
            array_merge(
                array( array_merge(
                    $wp_registered_sidebars[$sidebar_id],
                    array( 'widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name'] ) )
                ),
                $wp_registered_widgets[$id]['params']
            ));
            
            $classname = $wp_registered_widgets[$id]['classname'];
            $params[0]['before_widget'] = sprintf( $params[0]['before_widget'], $id, $classname );
            
            ob_start();
            call_user_func_array( $callback, $params );
            $output = ob_get_clean();
            
            return $output;
            
        }
        
        
        public function shortcode_info( $widget_instance ) {
            
            echo '<p><strong>' . __( 'Shortcode', 'ews' ) . ':</strong><br>';
            
            if ( $widget_instance->number == '__i__' ) {
                echo __( 'Save the widget first!', 'ews' ) . '</p>';
            } else {
                echo '<code>[ews_widget id="' . $widget_instance->id . '"]</code></p>';
            }
            
        }
        
    }
}

// Get the Easy Widgets Shortcode plugin running
Easy_Widgets_Shortcode::instance();
