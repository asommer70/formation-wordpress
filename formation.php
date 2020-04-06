<?php
/*
Plugin Name: Fromation for WordPress
Plugin URI: https://formation.thehoick.com/
Version: 0.0.2
Author: Adam Sommer
Description: Simple form handling with workflows.
*/

defined( 'ABSPATH' ) or die( 'No please!' );

//
// Add link in Admin Menu.
//
function formation_admin_menu() {
  add_menu_page(
    'Formation',
    'Formation',
    'manage_options',
    'formation',
    '',
    plugins_url('/formation-wordpress/assets/icon-20x20.svg')
  );

  add_submenu_page(
    'formation',
    'Forms',
    'Forms',
    'manage_options',
    'formation',
    'formation_forms'
  );

  add_submenu_page(
    'formation',
    'Formation Inbox',
    'Inbox',
    'manage_options',
    'formation-inbox',
    'formation_inbox'
  );

  add_submenu_page(
    'formation',
    'Formation Settings',
    'Settings',
    'manage_options',
    'formation-settings',
    'formation_settings'
  );
}
add_action('admin_menu', 'formation_admin_menu');


//
// Admin pages.
//
function formation_forms() {
  global $wpdb;

  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permission to access this page.');
  }

  //
  // Handle Form CRUD operations.
  //
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['new_form'])) {
      $wpdb->insert(
      	'formation_forms',
      	[
          'name' => $_POST['name'],
          'html' => wp_specialchars_decode($_POST['html']),
          'email' => $_POST['email'],
          'confirmation' => wp_specialchars_decode($_POST['confirmation']),
          'created_at' => current_time( 'mysql' )
        ]
      );
      $flash = $_POST['name'] .' created!';
    } elseif (isset($_POST['update_form'])) {
      $wpdb->update(
        'formation_forms',
        [
          'name' => $_POST['name'],
          'html' => wp_specialchars_decode($_POST['html']),
          'email' => $_POST['email'],
          'confirmation' => wp_specialchars_decode($_POST['confirmation'])
        ],
        ['id' => $_POST['form_id']]
      );
      $flash = $_POST['name'] .' updated!';
    } elseif (isset($_POST['delete_form'])) {
      $wpdb->delete('formation_forms', ['ID' => $_POST['form_id']]);
      $flash = $_POST['name'] .' deleted!';
    }
  }

  //
  // Decide what admin page to display.
  //
  if (isset($_GET['formation_action'])) {
    if (isset($_GET['form_id'])) {
      $form = $wpdb->get_results("SELECT * FROM formation_forms where id = ". $_GET['form_id'])[0];
    }
    include(__DIR__ .'/admin/'. $_GET['formation_action'] .'.php');
  } else {
    $forms = $wpdb->get_results("SELECT * FROM formation_forms");
    include(__DIR__ .'/admin/forms.php');
  }
}

function formation_inbox() {
  global $wpdb;

  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permission to access this page.');
  }

  if (isset($_GET['formation_action'])) {
    if (isset($_GET['form_id'])) {
      $input = $wpdb->get_results("SELECT * FROM formation_inputs where id = ". $_GET['input_id'])[0];
      $form = $wpdb->get_results("SELECT * FROM formation_forms where id = ". $_GET['form_id'])[0];
      include(__DIR__ .'/admin/'. $_GET['formation_action'] .'.php');
    }
  } else {
    $offset = '';
    if (isset($_GET['paged']) && $_GET['paged'] != 1) {
      $offset = ', '. $_GET['paged'] * 10;
    }
    $query = "select formation_inputs.*, formation_forms.name, (select count(*) from formation_inputs) as total
                                 from formation_inputs
                                 LEFT OUTER JOIN formation_forms on formation_inputs.form_id = formation_forms.id
                                 group by formation_inputs.id desc
                                 limit 10 ". $offset .";";
    $forms = $wpdb->get_results($query);

    include(__DIR__ .'/admin/inbox.php');
  }
}

function formation_settings() {
  global $wpdb;

  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permission to access this page.');
  }

  $from_email = get_option('formation_from_email');
  $form_css = get_option('formation_form_css');

  // Save/update settings using the wp-options table.
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_settings'])) {
      $new_from_email = filter_input(INPUT_POST, 'from_email', FILTER_SANITIZE_SPECIAL_CHARS);
      $new_form_css = filter_input(INPUT_POST, 'form_css', FILTER_SANITIZE_SPECIAL_CHARS);

      if (isset($from_email)) {
        update_option( 'formation_from_email', $new_from_email );
        $flash = 'From Email saved!';
      } else {
        add_option( 'formation_from_email', $new_from_email, '', 'no' );
        $flash = 'From Email saved!';
      }

      if (isset($form_css)) {
        update_option( 'formation_form_css', $new_form_css );
        $flash = 'Form CSS saved!';
      } else {
        add_option( 'formation_form_css', $new_form_css, '', 'no' );
        $flash = 'Form CSS saved!';
      }

      $from_email = $new_from_email;
      $form_css = $new_form_css;
    }
  }

  include(__DIR__ .'/admin/settings.php');
}

//
// Shortcode handling.
//
function formation_shortcode($atts, $content = null) {
  global $wpdb;
  global $post;
  extract(shortcode_atts(['id' => ''],$atts));

  $form = $wpdb->get_results("SELECT * FROM formation_forms where id = ". $id)[0];

  $content .= '<br/><br/>';

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add the form confirmation message to the page content.
    $content .= $form->confirmation;

    // Save data from $_POST into the database.
    $wpdb->insert(
      'formation_inputs',
      [
        'data' => json_encode($_POST['data'], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT),
        'form_id' => $form->id,
        'created_at' => current_time( 'mysql' )
      ]
    );

    // Send email that a form has been submitted and attach data.
    $from_email = get_option('formation_from_email');
    $headers = 'From: '. $from_email ."\r\n".
        'Reply-To: '. $from_email ."\r\n";

    $to      = $form->email;
    $subject = 'Form: '. $form->name .' submitted.';
    $message = '';
    foreach($_POST['data'] as $field => $value) {
      $message .= $field .":\n-------------------\n". stripslashes($value) ."\n\n";
    }
    $message .= "\n\n\n-------------------------------------------------------\n";
    $message .= "To view the complete form: \t". get_bloginfo('url') .'/wp-admin/admin.php?page=formation-inbox';

    // Send email.
    mail($to, $subject, $message, $headers);
  } else {
    $content .= stripslashes($form->html);
  }

  return $content;
}
add_shortcode('formation', 'formation_shortcode');


//
// Add admin CSS and JavaScripts.
//
function formation_admin_css_and_js() {
  wp_enqueue_script('admin_js', plugins_url('formation-wordpress/assets/js/admin.js'), ['jquery'], '', true);
}
add_action('admin_head', 'formation_admin_css_and_js');


//
// Enqueue code editor and settings for manipulating HTML.
// See assets/js/admin.js for JavaScript that actually activates the CodeMirror editor.
//
add_action( 'admin_enqueue_scripts', function() {
  $form_css = get_option('formation_form_css');

  // Only apply the Form CSS to the Inbox page.
  if (isset($_GET['formation_action']) && $_GET['formation_action'] == 'input') {
    wp_enqueue_style('form_css', $form_css);
  }

  $settings = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
} );


//
// Installation functions.
//
function formation_create_tables() {
  global $wpdb;
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

  $charset_collate = $wpdb->get_charset_collate();

  $forms = "CREATE TABLE formation_forms (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    name tinytext NOT NULL,
    html text NOT NULL,
    email tinytext NOT NULL,
    confirmation text NOT NULL,
    created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate;";

  dbDelta( $forms );

  $inputs = "CREATE TABLE formation_inputs (
    id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    data text NOT NULL,
    created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    form_id mediumint NOT NULL,
    KEY (form_id),
    FOREIGN KEY (form_id) REFERENCES formation_forms(id)
  ) $charset_collate;";

  dbDelta( $inputs );

  add_option( "formation_db_version", "0.1" );
}
register_activation_hook( __FILE__, 'formation_create_tables' );
