<?php
/**
 * @wordpress-plugin
 * Plugin Name:       User Management for WordPress
 * Plugin URI:        https://wpexperts.io
 * Description:       Import and Export Users.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            WPExperts
 * Author URI:        https://wpexperts.io
 * Text Domain:       uiewp
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed

if(!class_exists('UIEWP_UsersImportExport')) {
   class UIEWP_UsersImportExport{
      function __construct() {
         $this->uiewp_includes();
         new UIEWP_Model();
         add_action( 'admin_menu', array( $this, 'uiewp_register_admin_menu' ) );
         add_action( 'admin_init', array($this, 'uiewp_settings') );
         add_action( 'admin_init', array( $this, 'uiewp_register_export_field_settings' ) );
         add_action( 'admin_enqueue_scripts', array($this, 'uiewp_scripts') );
      }

      public function uiewp_scripts() {
         if( get_current_screen()->id === 'users_page_import-export-users-wpexperts' ) {
            wp_enqueue_style( 'select2-css', IEUW_ASSETS . "css/select2.min.css", array(), false );
            wp_enqueue_style( 'imp-exp-style', IEUW_ASSETS . "css/main.css", array(), false );
            wp_enqueue_script( 'select2-js', IEUW_ASSETS . "js/select2.min.js", uniqid(), array( 'jquery' ), true );
            wp_enqueue_script( 'imp-exp-users', IEUW_ASSETS . "js/scripts.js", uniqid(), array( 'jquery' ), true );
            wp_localize_script( 'imp-exp-users', 'ajaxURL', array(admin_url( 'admin-ajax.php' )) );
         }

      }

      public function uiewp_settings() {
         register_setting( 'import-export-users', 'import-export-users ');
         add_settings_section( 'export-section', __('Export Users & Roles', IEUW_TEXT_DOMAIN), array($this, 'uiewp_export_section'), 'import-export-users-wpexperts' );
         add_settings_field( 'export_all_users', __('Export All Users', IEUW_TEXT_DOMAIN), array($this, 'uiewp_export_all_users'), 'import-export-users-wpexperts', 'export-section' );
         add_settings_field( 'export_users_by_roles', __('Export Users by roles', IEUW_TEXT_DOMAIN), array($this, 'uiewp_export_users_by_roles'), 'import-export-users-wpexperts', 'export-section' );
         add_settings_field( 'export_specific_users', __('Export specific users', IEUW_TEXT_DOMAIN), array($this, 'uiewp_export_specific_users'), 'import-export-users-wpexperts', 'export-section' );
         add_settings_field( 'export_roles_caps', __('Export WP Roles and Capabilities', IEUW_TEXT_DOMAIN), array($this, 'uiewp_export_roles_caps'), 'import-export-users-wpexperts', 'export-section' );
         add_settings_field( 'export_button', sprintf('<button class="btn btn-accept" id="wp_export_users" type="button" id="export_all_users">%s</button>', __('Export', IEUW_TEXT_DOMAIN)), array($this, 'uiewp_export_button'), 'import-export-users-wpexperts', 'export-section' );
         add_settings_field( 'export_messsages', '<div class="error-div"></div>', array($this, 'uiewp_export_messsages'), 'import-export-users-wpexperts', 'export-section' );

         add_settings_section( 'import-section', __('Import Users & Roles', IEUW_TEXT_DOMAIN), array($this, 'uiewp_import_section'), 'import-export-users-wpexperts&tab=import' );
         add_settings_field( 'import_users', __('Import WP Users', IEUW_TEXT_DOMAIN), array($this, 'uiewp_import_users'), 'import-export-users-wpexperts&tab=import', 'import-section' );
         add_settings_field( 'import_roles_caps', __('Import WP Roles and Capabilities', IEUW_TEXT_DOMAIN), array($this, 'uiewp_import_roles_caps'), 'import-export-users-wpexperts&tab=import', 'import-section' );
         add_settings_field( 'import_file_field', __('Browse CSV file', IEUW_TEXT_DOMAIN), array($this, 'uiewp_import_file_field'), 'import-export-users-wpexperts&tab=import', 'import-section' );
         add_settings_field( 'import_button', sprintf('<button class="btn btn-accept" id="wp_import_users_roles" type="button" id="">%s</button>', __('Import Users', IEUW_TEXT_DOMAIN)), array($this, 'uiewp_import_button'), 'import-export-users-wpexperts&tab=import', 'import-section' );
         
         add_settings_section( 'settings-section', '', array($this, 'uiewp_settings_section'), 'import-export-users-wpexperts&tab=settings' );
      }

      public function uiewp_includes() {
         include plugin_dir_path(__FILE__) . "includes/config.php";
         include plugin_dir_path(__FILE__) . "includes/model.php";
         
      }

      public function uiewp_register_admin_menu() {
         add_users_page(
            __( 'Import / Export', IEUW_TEXT_DOMAIN ),
            __( 'Import / Export', IEUW_TEXT_DOMAIN ),
            'read',
            'import-export-users-wpexperts',
            array($this, 'uiewp_settings_page')
        );
      }

      public function uiewp_register_export_field_settings() {
         $data = array(
             'ID' => 'ID',
             'user_login' => 'user_login',
             'user_pass' => 'user_pass',
             'user_nicename' => 'user_nicename',
             'user_email' => 'user_email',
             'user_url' => 'user_url',
             'user_registered' => 'user_registered',
             'user_activation_key' => 'user_activation_key',
             'user_status' => 'user_status',
             'display_name' => 'display_name',
             'role' => 'role',
             'nickname' => 'nickname',
             'first_name' => 'first_name',
             'last_name' => 'last_name',
             'description' => 'description',
             'rich_editing' => 'rich_editing',
             'syntax_highlighting' => 'syntax_highlighting',
             'comment_shortcuts' => 'comment_shortcuts',
             'use_ssl' => 'use_ssl',
             'admin_color' => 'admin_color',
             'show_admin_bar_front' => 'show_admin_bar_front',
         );

         add_option( 'uiewp_export_field', $data );
      }

      public function uiewp_settings_page() {
         require plugin_dir_path(__FILE__) . "includes/settings_page.php";
     }

      public function uiewp_export_section() {
         return;
      }

      public function uiewp_export_all_users() {
         echo '<label class="switch" id="switch"><input type="checkbox" name="export_all_users" checked><span class="slider round"></span></label>';
         echo sprintf('<label for="label_export_all_users"> %s</label>', __('Export All Users of Wordpress', IEUW_TEXT_DOMAIN));
      }

      public function uiewp_export_users_by_roles() {
         echo '<label class="switch" id="switch"><input type="checkbox" name="export_users_by_roles"><span class="slider round"></span></label>';
         echo sprintf('<label for="export_users_by_roles"> %s</label>', __('Export Users by roles', IEUW_TEXT_DOMAIN));
         echo '<div class="roles_checkboxes">';
         $roles = new WP_Roles();
		foreach($roles->role_names as $key => $role) {
            echo '<br /><label class="switch" id="switch"><input class="wp_roles" type="checkbox" value="'.esc_attr($key).'" name="selected_roles"><span class="slider round"></span></label>&nbsp;<label for="label_'.esc_attr($role).'">'.esc_attr($role).'</label>';
         }
         echo '</div>';

      }

      public function uiewp_export_specific_users() {
         $users = get_users( array( 'all' ) );
         echo '<div class="export_specific_users"><label class="switch" id="switch"><input id="export_specific_users" type="checkbox" name="export_specific_users"><span class="slider round"></span></label>';
         echo sprintf('<label for="export_specific_users"> %s</label>', __('Export specific users', IEUW_TEXT_DOMAIN));
         echo "</div><br />";
         echo '<div class="select2-field-users"><select class="wp_users-export" name="users[]" multiple="multiple" style="width: 50%">';
         foreach ( $users as $user ) {
            echo '<option value="'.esc_attr($user->user_email).'">' .esc_attr($user->display_name).'</option>';
         }
         echo '</select></div>';
      }

      public function uiewp_export_roles_caps() {
         $roles = wp_roles();
         echo '<div class="export_roles_caps"><label class="switch" id="switch"><input id="export_roles_caps" type="checkbox" name="export_roles_caps"><span class="slider round"></span></label>';
         echo sprintf('<label for="export_roles_caps"> %s</label>', __('Export roles and capabilities', IEUW_TEXT_DOMAIN));
         echo "</div><br />";
         echo '<div class="select2-field-roles"><select class="wp_roles-export" name="roles[]" multiple="multiple" style="width: 50%">';
         $index = -1;
         foreach ( $roles->role_names as $role ) {
            $index++;
            echo '<option value="'.esc_attr(array_keys($roles->role_names)[$index].' ,' . $role).'">' .esc_attr($role).'</option>';
         }
         echo sprintf('</select><p id="field-message">%s</p></div>', __('Leave blank for all.', IEUW_TEXT_DOMAIN));
      }


      public function uiewp_export_button() {
         echo '<div class="loader flexbox"><div><div class="dot-loader"></div><div class="dot-loader dot-loader--2"></div><div class="dot-loader dot-loader--3"></div></div></div>';
      }

      public function uiewp_export_messsages() {
         return;
      }

      public function uiewp_import_section() {
         return;
      }

      public function uiewp_import_users() {
         echo '<label class="switch" id="switch"><input type="checkbox" name="import_users" checked><span class="import slider round"></span></label>';
         echo sprintf('<label style="margin-left:10px;" for="label_import_users">%s</label>', __('Import Users', IEUW_TEXT_DOMAIN));
         echo '<br />';
         echo '<div id="import_update_users"><label class="switch" id="switch"><input type="checkbox" name="import_update_users"><span class="import slider round"></span></label>';
         echo sprintf('<label style="margin-left:10px;" for="label_import_update_users">%s</label></div>', __('Update Users', IEUW_TEXT_DOMAIN));
      }

      public function uiewp_import_roles_caps() {
         echo '<label class="switch" id="switch"><input type="checkbox" name="import_roles_caps"><span class="import slider round"></span></label>';
         echo sprintf('<label style="margin-left:10px;" for="label_import_users">%s</label>', __('Import Roles and Capabilities', IEUW_TEXT_DOMAIN));
      }

      public function uiewp_import_file_field() {
         echo sprintf('<input class="input-file" id="file-to-import" accept=".csv" type="file"><label tabindex="0" for="file-to-import" class="input-file-trigger">%s</label><p class="file-return"></p>
         ', __('Select a file...', IEUW_TEXT_DOMAIN));
      }

      public function uiewp_import_button() {
         echo '<div class="flexbox"><div><div class="dot-loader"></div><div class="dot-loader dot-loader--2"></div><div class="dot-loader dot-loader--3"></div></div></div>';
      }

      public function uiewp_settings_section() {
         require_once(IEUW_INCLUDES . 'view/settings-view.php');
      }

   }
  $instance = new UIEWP_UsersImportExport();  
 }