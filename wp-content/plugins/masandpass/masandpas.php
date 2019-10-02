<?php

/**
 * Plugin Name: Mas & Pas
 * Description: Prototyping custom features on the top of wordpress.
 */


//Add shortscode
function maps_wizzard()
{
    // add vue
    wp_enqueue_script('maps_wizzard_js');
    wp_enqueue_script('maps_vue');
    wp_enqueue_style('maps_wizzard_css');
    // build string
    $placeholder = "<div id='divWpVue'>"
        . "<maps></maps>"
        . "</div>";


    return $placeholder;
}
add_shortcode('maps_vue', 'maps_wizzard');

function maps_load_vuescripts()
{
    wp_register_script('maps_wizzard_js', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', 'maps_verification_wizzard');
    wp_register_script('maps_vue', plugin_dir_url( __FILE__ ).'assets/js/verification-wizzard.js', 'maps_wizzard_js' );
    wp_register_style('maps_wizzard_css', plugin_dir_url( __FILE__ ).'assets/css/wizzard.css' );
}
add_action('wp_enqueue_scripts', 'maps_load_vuescripts');
