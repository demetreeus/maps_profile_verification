<?php

/**
 * Plugin Name: Mas & Pas
 * Description: Prototyping custom features on the top of wordpress.
 */


//Add shortscode
function maps_wizzard()
{
    // add vue
    wp_enqueue_script('maps_axios');
    wp_enqueue_script('maps_wizzard_js');
    wp_localize_script( 'maps_wizzard_js', 'maps_ajax',
            array( 'url' => admin_url( 'admin-ajax.php' ) ) );
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
    wp_register_script('maps_axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js');
    wp_register_script('maps_vue', plugin_dir_url( __FILE__ ).'assets/js/verification-wizzard.js', 'maps_wizzard_js' );
    wp_register_style('maps_wizzard_css', plugin_dir_url( __FILE__ ).'assets/css/wizzard.css' );
}

function maps_handle_document()
{
    if(isset($_FILES['document'])){
        $document = $_FILES['document'];
    } else {
        wp_send_json(['error' => 'please provide a document'], 418);
    }

    $stored = maps_store_document($document);

    if(!$stored){
        wp_send_json(['error' => 'could not store image'], 500);
    }

    wp_send_json(['success' => true, 'file' => $stored]);
}

function maps_store_document($doc)
{
    if(! $doc['error'] ) {
        try{
            $user_id = get_current_user_id();
            $_POST['action'] = 'wp_handle_upload';
            $upload_overrides = array( 'test_form' => false );
            $upload = wp_handle_upload( $doc, $upload_overrides );
            update_user_meta( $user_id, 'maps_document', $upload );
            error_log(print_r($upload, true));
            //return get_user_meta($user_id, 'maps_document', true);
            return $upload;
        } catch (Exception $e) {
            error_log(print_r($e->getMessage(), true));
            return false;
        }
        

    } else {
        return false;
    }
}

add_action('wp_enqueue_scripts', 'maps_load_vuescripts');
add_action( 'wp_ajax_maps_document', 'maps_handle_document' );
add_action( 'wp_ajax_nopriv_maps_document', 'maps_handle_document' );
