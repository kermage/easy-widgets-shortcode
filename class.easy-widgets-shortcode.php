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
            add_action( 'widgets_admin_page', array( $this, 'sidebar_shortcode_info' ), 100 );
            add_action( 'in_widget_form', array( $this, 'widget_shortcode_info' ), 100, 3 );
            add_action( 'widgets_init', array( $this, 'shortcodes_area' ), 100 );
            
        }
        
        
        public function sidebar_shortcode( $atts ) {
            
            extract( shortcode_atts( array(
                'id'  => ''
            ), $atts ) );
            
            if ( empty( $id ) )
                return;
            
            ob_start();
            dynamic_sidebar( $id );
            $output = ob_get_clean();
            
            return $output;
            
        }
        
        
        public function widget_shortcode( $atts ) {
            
            extract( shortcode_atts( array(
                'id'    => '',
                'wrap'  => '',
                'title'  => ''
            ), $atts ) );
            
            if ( empty( $id ) )
                return;
            
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
            
            if( ! $wp_registered_sidebars[$sidebar_id] )
                return;
            
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
            
            if ( ! empty( $wrap ) ) {
                if ( $wrap == 'false' ) {
                    $params[0]['before_widget'] = '';
                    $params[0]['after_widget'] = '';
                } else {
                    preg_match( '/<\/([^>]+)>/', $params[0]['after_widget'], $def_wrap );
                    $params[0]['before_widget'] = str_replace( $def_wrap[1], $wrap, $params[0]['before_widget'] );
                    $params[0]['after_widget'] = '</' . $wrap . '>';
                }
            }
            
            if ( ! empty( $title ) ) {
                preg_match( '/<\/([^>]+)>/', $params[0]['after_title'], $def_tag );
                if ( $title != 'false' ) {
                    $params[0]['before_title'] = str_replace( $def_tag[1], $title, $params[0]['before_title'] );
                    $params[0]['after_title'] = '</' . $title . '>';
                }
            }
            
            ob_start();
            call_user_func_array( $callback, $params );
            $output = ob_get_clean();
            
            if ( ! empty( $title ) ) {
                if ( $title == 'false' ) {
                    $output = preg_replace( '/' . $params[0]['before_title'] . '(.*?)<\/' . $def_tag[1] . '>/', '', $output );
                }
            }
            
            return $output;
            
        }
        
        
        public function sidebar_shortcode_info() {

            ob_start(); ?>
            
            <script type="text/javascript" id="ews-info">
                jQuery( document ).ready( function( $ ) {
                    $( '.widgets-sortables' ).not( '#wp_inactive_widgets, #ews-sidebar' ).each( function() {
                        if ( $( this ).find( '.sidebar-description' ).length > 0 ) {
                            $( this ).find( '.sidebar-description' ).append( '<p class="description"><strong>Shortcode:</strong><br><code>[ews_sidebar id="' + this.id + '"]</code></p>' );
                        } else {
                            $( this ).find( '.sidebar-name' ).after( '<div class="sidebar-description"><p class="description"><strong>Shortcode:</strong><br><code>[ews_sidebar id="' + this.id + '"]</code></p></div>' );
                        }
                    } );
                } );
            </script>

            <?php
            $output = ob_get_clean();
            
            echo $output;
            
        }
        
        
        public function widget_shortcode_info( $widget, $return, $instance ) {
            
            echo '<p><strong>' . __( 'Shortcode', 'ews' ) . ':</strong><br>';
            
            if ( $widget->number == '__i__' ) {
                echo __( 'Save the widget first!', 'ews' ) . '</p>';
            } else {
                echo '<code>[ews_widget id="' . $widget->id . '"]</code></p>';
            }
            
        }
        
        
        public function shortcodes_area() {
            register_sidebar( array(
                'name'          => __( 'Easy Widgets Shortcode', 'ews' ),
                'id'            => 'ews-sidebar',
                'description'   => __( 'Add widgets here to be used as shortcodes.', 'ews' ),
                'class'         => '',
                'before_widget' => '<div id="%1$s" class="%2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3>',
                'after_title'   => '</h3>'
            ) );
        }
        
    }
}

// Get the Easy Widgets Shortcode plugin running
Easy_Widgets_Shortcode::instance();
