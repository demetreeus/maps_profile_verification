<?php

/**
 * Plugin Name: Mas & Pas
 * Description: Prototyping custom features on the top of wordpress.
 */

use um\core\Date_Time;

require('vendor/autoload.php');

/*
/   Frontend
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
    wp_enqueue_script('maps_validate');
    wp_enqueue_script('maps_alert');
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
    wp_register_script('maps_validate', plugin_dir_url( __FILE__ ).'assets/js/node_modules/jquery-validation/dist/jquery.validate.min.js');
    wp_register_script('maps_alert', plugin_dir_url( __FILE__ ).'assets/js/node_modules/sweetalert2/dist/sweetalert2.min.js');
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

function maps_handle_info()
{
    $input = $_POST;
    $user_id = get_current_user_id();

    // compose data
    try{
        foreach(['maps_company_name', 'maps_firstname', 'maps_lastname', 'maps_phone_number' ] as $field){
            if(isset($_POST[$field])){
                $meta[$field] = sanitize_text_field($_POST[$field]);
            } else {
                throw new Exception("aint like fields");
            }
        }

        foreach($meta as $key => $value){
            update_user_meta($user_id, $key, $value);
        }
    } catch(Exception $e) {
        wp_send_json(['error' => $e->getMessage()], 418); 
    }

    wp_send_json(['success' => true]);
}

function maps_handle_payment()
{
    $input = $_POST;
    $user_id = get_current_user_id();
    error_log(print_r($input, true));
    
    if(!isset($input['stripeToken'])) {
        wp_send_json(['error' => 'missing token'], 418); 
    }

    $token = $input['stripeToken'];
    // compose data
    try{
        \Stripe\Stripe::setApiKey('sk_test_5eMtw9Slaxf5ErolPm17PBfd00zQGsi2xW');
        $charge = \Stripe\Charge::create(['amount' => 2000, 'currency' => 'gbp', 'source' => $token]);
        update_user_meta($user_id, 'maps_paid_at', date('Y-m-d H:i:s'));
        update_user_meta($user_id, 'maps_transaction_id', $charge->id);

        maps_complete_application($user_id, $charge);
        
        wp_send_json(['success' => true]);
    } catch(Exception $e) {
        wp_send_json(['error' => $e->getMessage()], 418); 
    }
}

add_action('wp_enqueue_scripts', 'maps_load_vuescripts');
add_action( 'wp_ajax_maps_document', 'maps_handle_document' );
add_action( 'wp_ajax_nopriv_maps_document', 'maps_handle_document' );
add_action( 'wp_ajax_maps_info', 'maps_handle_info' );
add_action( 'wp_ajax_nopriv_maps_info', 'maps_handle_info' );
add_action( 'wp_ajax_maps_payment', 'maps_handle_payment' );
add_action( 'wp_ajax_nopriv_maps_payment', 'maps_handle_payment' );


/*
/   Admin
*/

function maps_complete_application($user_id, $charge) {
    $data = maps_compose_user_data($user_id);
    $body = maps_render_email($data);
    $to = get_option('admin_email');
    $subject = 'Mas&Pas Business Verification';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($to, $subject, $body, $headers);
}

function maps_compose_user_data($user_id) {
    $fields = maps_custom_define();
    $data['ID'] = $user_id;
    
    foreach($fields as $field => $label){
        $data[$field] = get_user_meta($user_id, $field, true);
    }

    return $data;
}

function maps_render_email($data) {
    $str = "<h4>New Business Verification application</h4>";
    $str .= "<p>Company Name: <b>".$data['maps_company_name']."</b></p>";
    $str .= "<p>First Name: <b>".$data['maps_firstname']."</b></p>";
    $str .= "<p>Last Name: <b>".$data['maps_lastname']."</b></p>";
    $str .= "<p>Phone Number: <b>".$data['maps_phone_number']."</b></p>";
    $str .= "<p><a href='".get_site_url()."/wp-admin/user-edit.php?user_id=".$data['ID']."'>Visit User's Profile</a></p>";

    return $str;
}

// Hooks near the bottom of the profile page (if not current user) 
add_action('show_user_profile', 'maps_show_extra_profile_fields');
add_action('edit_user_profile', 'maps_show_extra_profile_fields');
function maps_custom_define() {
    $custom_meta_fields = array();
    $custom_meta_fields['maps_company_name'] = 'Company Name:';
    $custom_meta_fields['maps_firstname'] = 'First Name:';
    $custom_meta_fields['maps_lastname'] = 'Last Name:';
    $custom_meta_fields['maps_phone_number'] = 'Phone Number:';
    $custom_meta_fields['maps_paid_at'] = 'Applied at:';
    $custom_meta_fields['maps_transaction_id'] = 'Transaction ID:';
    $custom_meta_fields['maps_document'] = 'Document:';
    $custom_meta_fields['maps_verified_at'] = 'Verified at:';
    return $custom_meta_fields;
  }

  // @param WP_User $user
function maps_show_extra_profile_fields($user) {
    print('<h3>Business Verification</h3>');
  
    print('<table class="form-table">');
  
    $meta_number = 0;
    $fields = maps_custom_define();
    foreach ($fields as $meta_field_name => $meta_disp_name) {
      $meta_number++;
      print('<tr>');
      print('<th><label for="' . $meta_field_name . '">' . $meta_disp_name . '</label></th>');
      print('<td>');
      if($meta_field_name == 'maps_paid_at'){
        try {
            $paid_at = (new DateTime(get_user_meta($user->ID, $meta_field_name, true )))->format('d M Y H:s');
        } catch (Exception $e) {
            $paid_at = '';
        }
    
        print("<p>".$paid_at."</p>");
    } else if($meta_field_name == 'maps_document'){
        try {
            $document = get_user_meta($user->ID, $meta_field_name, true )['url'];
        } catch (Exception $e) {
            $document = null;
        }
        if($document){
            print('<a href="'.$document.'" target="_blank">Open document</a>');
        }
        
      } else if($meta_field_name == 'maps_verified_at'){
        $verifiedAt = get_user_meta($user->ID, 'maps_verified_at', true);

        if(empty($verifiedAt)){
            print('<button id="verify_user">Verify Now</button>');
            print('<input id="user_verified_at" type="hidden" name="maps_verified_at" />');
        } else {
            $verifiedAt = (new DateTime(get_user_meta($verifiedAt, true )))->format('d M Y H:s');
            print("<p>".$verifiedAt."</p>");
        }
        
        
      } else {
        print('<p>'.esc_attr( get_user_meta($user->ID, $meta_field_name, true ) ) . '</p>');
      }
      print('<span class="description"></span>');
      print('</td>');
      print('</tr>');
    }
    print('</table>');
  }

  function maps_store_verified_at($user_id) {
    if (!current_user_can('edit_user', $user_id))
      return false;
  
    if(!empty($_POST['maps_verified_at'])){
        update_user_meta( $user_id, 'maps_verified_at', $_POST['maps_verified_at'] );

    }
  }
  add_action('edit_user_profile_update', 'maps_store_verified_at');
  add_action('personal_options_update', 'maps_store_verified_at');

  function maps_load_admin_script() {
    wp_enqueue_script( 'admin-js', plugins_url( 'masandpass/assets/js/admin.js' , dirname(__FILE__) ) );
  }
  add_action('admin_enqueue_scripts', 'maps_load_admin_script');

  add_action( 'um_cover_area_content', 'maps_profile_cover_area_content', 10, 1 );
  function maps_profile_cover_area_content( $user_id ) {
    // your code here
    $verifiedAt = get_user_meta($user_id, 'maps_verified_at', true);
    
    if(!empty($verifiedAt)) {
        $badgeClass = 'show';
    } else {
        $badgeClass = 'hide';
    }

    $badge = "<div id='verified_badge' class='$badgeClass'>".
    "<i class='checkmark um-faicon-check'></i>".
    "</div>";

    echo $badge;
  }