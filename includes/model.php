<?php

class UIEWP_Model {

    protected static $option;

    public function __construct() {
        self::$option =  get_option( 'uiewp_export_field' );
        // Exporting Request hooks
        add_action( 'wp_ajax_export_all_users_ajax', array($this, 'uiewp_export_all_users_callback'));
        add_action( 'wp_ajax_export_users_by_roles', array($this, 'uiewp_export_users_by_roles_callback'));
        add_action( 'wp_ajax_export_specific_users', array($this, 'uiewp_export_specific_users_callback'));
        add_action( 'wp_ajax_export_roles_caps', array($this, 'uiewp_export_roles_caps_callback'));
        
        // Importing Request Hooks
        add_action( 'wp_ajax_import_users', array($this, 'uiewp_import_users_callback'));
        add_action( 'wp_ajax_import_roles_caps', array($this, 'uiewp_import_roles_caps_callback'));
        
        // dependencies
        require_once("config.php");
        $this->uiewp_export_fields_settings();

    }

    public function uiewp_export_all_users_callback() {   
        $users = get_users(array('all'));
        $count = count( $users );

        $file_data = $this->uiewp_get_files('all_users');
        $file = fopen($file_data['file_path'], "w");
        $headers = $this->uiewp_write_csv_header();
        
        fputcsv($file, $headers);
        foreach($users as $key => $user) {
            $user_array = (array) $user;
            $data = (array)$user_array['data'];
            $body = $this->uiewp_sync_user_data_to_csv( $data, $user_array );

            fputcsv($file, $body);
        }
        fclose($file);
        $response = array(
            'file_url' => $file_data['file_url'],
            'count' => $count . ' ' . 'Users exported successfully.'            
        );
        wp_send_json($response);
    }

    public function uiewp_export_users_by_roles_callback() {
        if(isset($_POST['roles'])) {
            $roles = array();
            foreach( $_POST['roles'] as $role ) {
                $roles[] = sanitize_text_field( $role );
            }
            $count = 0;

            $file_data = $this->uiewp_get_files('by_roles');
            
            $file = fopen($file_data['file_path'], "w");

            $headers = $this->uiewp_write_csv_header();
            fputcsv($file, $headers);
            
            $users_email = $this->uiewp_get_users_email_by_role($roles);
            $data = array();
            foreach( $users_email as $email ) {
                $count++;
                $user = (array) get_user_by( 'email', $email );
                $data = (array) $user['data'];
                $body = $this->uiewp_sync_user_data_to_csv( $data, $user );
                fputcsv($file, $body);
                
            }
            fclose($file);
           
        }
        $response = array(
            'file_url' => $file_data['file_url'],
            'count' => $count . ' ' . 'Users exported successfully.'            
        );
        wp_send_json($response);
    }

    private function uiewp_get_users_email_by_role($roles){
        $emails = array();
        foreach( $roles as $index => $role ) {
            $users= get_users( ['role'=> $role ] );
            foreach($users as $user) {
                $emails[$user->display_name] = $user->user_email;
            }
        }
        return $emails;
    }

    public function uiewp_export_specific_users_callback() {
        if( $_POST['users'] ) {

            $users = array();
            foreach( $_POST['users'] as $s_user ) {
                $users[] = sanitize_text_field( $s_user );
            }
            $count = count($users);
           
            $file_data = $this->uiewp_get_files('specific_users');
            $file = fopen($file_data['file_path'], "w");

            $headers = $this->uiewp_write_csv_header();
            fputcsv($file, $headers);

            foreach( $users as $email ) {
                $user = (array) get_user_by( 'email', $email );
                $data = (array) $user['data'];
                $body = $this->uiewp_sync_user_data_to_csv( $data, $user );
                fputcsv($file, $body);
            }
            fclose($file);

            $response = array(
                'file_url' => $file_data['file_url'],
                'count' => $count . ' ' . 'Users exported successfully.'            
            );
            wp_send_json($response);
        }
    }

    private function uiewp_sync_user_data_to_csv( $data, $user_array ) {
        if(!isset(self::$option['user_login'])) {
            unset($data['user_login']);
        }
        if(!isset(self::$option['user_pass'])) {
            unset($data['user_pass']);
        }
        if(!isset(self::$option['user_nicename'])) {
            unset($data['user_nicename']);
        }
        if(!isset(self::$option['user_url'])) {
            unset($data['user_url']);
        }
        if(!isset(self::$option['user_registered'])) {
            unset($data['user_registered']);
        }
        if(!isset(self::$option['user_activation_key'])) {
            unset($data['user_activation_key']);
        }
        if(!isset(self::$option['user_status'])) {
            unset($data['user_status']);
        }
        if(!isset(self::$option['display_name'])) {
            unset($data['display_name']);
        }
        if( isset( self::$option['role'] ) && self::$option['role'] != NULL ) {
            if( count($user_array['roles']) > 1) {
                $roles = '';
                foreach($user_array['roles'] as $role){
                    $roles .= $role;
                    $roles .= ", ";
                }
            } else {
                $roles = $user_array['roles'][0];
            }
            $data['roles'] = $roles;
        }
        if( isset( self::$option['nickname'] ) ) {
            $nickname = get_user_meta($data['ID'], 'nickname');
            $data['nickname'] = $nickname[0];
        }
        if( isset( self::$option['first_name'] ) ) {
            $first_name = get_user_meta($data['ID'], 'first_name');
            $data['first_name'] = $first_name[0];
        }
        if( isset( self::$option['last_name'] ) ) {
            $last_name = get_user_meta($data['ID'], 'last_name');
            $data['last_name'] = $last_name[0];
        }
        if( isset( self::$option['description'] ) ) {
            $description = get_user_meta($data['ID'], 'description');
            $data['description'] = $description[0];
        }
        if( isset( self::$option['rich_editing'] ) ) {
            $rich_editing = get_user_meta($data['ID'], 'rich_editing');
            $data['rich_editing'] = $rich_editing[0];
        }
        if( isset( self::$option['syntax_highlighting'] ) ) {
            $syntax_highlighting = get_user_meta($data['ID'], 'syntax_highlighting');
            $data['syntax_highlighting'] = $syntax_highlighting[0];
        }
        if( isset( self::$option['comment_shortcuts'] ) ) {
            $comment_shortcuts = get_user_meta($data['ID'], 'comment_shortcuts');
            $data['comment_shortcuts'] = $comment_shortcuts[0];
        }
        if( isset( self::$option['use_ssl'] ) ) {
            $use_ssl = get_user_meta($data['ID'], 'use_ssl');
            $data['use_ssl'] = $use_ssl[0];
        }
        if( isset( self::$option['admin_color'] ) ) {
            $admin_color = get_user_meta($data['ID'], 'admin_color');
            $data['admin_color'] = $admin_color[0];
        }
        if( isset( self::$option['show_admin_bar_front'] ) ) {
            $show_admin_bar_front = get_user_meta($data['ID'], 'show_admin_bar_front');
            $data['show_admin_bar_front'] = $show_admin_bar_front[0];
        }
        return $data;
    }

    public function uiewp_export_roles_caps_callback() {

        if($_POST['roles']) {
            $roles = array();
            foreach( $_POST['roles'] as $s_role ) {
                $roles[] = sanitize_text_field( $s_role );
            }
            $count = count($roles);
            $data = array();
                
            $file_data = $this->uiewp_get_files( 'roles_caps' );
            $file = fopen($file_data['file_path'], "w");

            $headers = array( "id", "role",'role_name', "capabilities" );
            fputcsv( $file, $headers );
            $id = 0;
            foreach( $roles as $role_arr ) {
                $id++;
                $role_value = explode(' ,', $role_arr);
               
                $role = ( array ) get_role( $role_value[0] );
                $data['id'] = $id;
                $data['role'] = $role['name']; 
                $data['role_name'] = $role_value[1];
                $caps = implode(":1 ,", array_keys($role['capabilities']));
                $caps.= ":1";
                $data['capabilities'] = $caps;

                fputcsv( $file, $data );

            }
            fclose($file);

            $response = array(
                'file_url' => $file_data['file_url'],
                'count' => $count . ' ' . 'roles and its capabilities exported successfully.'            
            );
            
        } else {
            $WP_Roles = new WP_Roles();
            $roles = $WP_Roles->roles;
            $count = count($roles);
            $data = array();

            $file_data = $this->uiewp_get_files( 'roles_caps' );
            $file = fopen($file_data['file_path'], "w");

            $headers = array( "id", "role",'role_name', "capabilities" );
            fputcsv( $file, $headers );
            $id = 0;

            foreach( $roles as $role_name => $role ) {
                $id++;

                $data['ID'] = $id;
                $data['role'] = $role_name;
                $data['role_name'] = $role['name'];
                $caps = implode(":1 ,", array_keys($role['capabilities']));
                $caps.= ":1";
                $data['capabilities'] = $caps;

               fputcsv( $file, $data );

            }
            fclose($file);

            $response = array(
                'file_url' => $file_data['file_url'],
                'count' => $count . ' ' . 'roles and its capabilities exported successfully.'            
            );
        }
        wp_send_json( $response );
    }

    public function uiewp_import_users_callback() {
        global $wpdb;
        $message = '';
        if( $_FILES["file"]["name"] != '' ) {
            $file_arr = explode(".", sanitize_file_name($_FILES["file"]["name"]));
            $file_ext = end($file_arr);
            if(!in_array($_FILES['file']['type'], ['text/csv', 'application/vnd.ms-excel', 'text/comma-separated-values']) && $file_ext != 'csv') {
                wp_send_json(array( 'message' => 'Unsupported Format' ));
                
            } else {
                $uploads_dir = wp_upload_dir()['basedir'] . 'imp-exp-users-wp/imports';
                wp_mkdir_p($uploads_dir);

                $source = $_FILES['file']['tmp_name'];
                $destination = trailingslashit($uploads_dir) . sanitize_file_name($_FILES['file']['name']);
                $status = move_uploaded_file($source, $destination);
                if($status) {
                    $WP_Roles = new WP_Roles();
                    $file = fopen($destination, "r");
                    $header = $this->uiewp_get_csv_header($file);
                    
                    if( in_array( "role_name", $header ) && in_array( "capabilities", $header ) ) {
                        wp_send_json( ['status' => true, 'message' => '<span style="color:red">Invalid File</span>'] );
                    }

                    $index = 0;
                    $success_imports = 0;
                    $option = self::$option;
                    $flipped_header = array_flip( $header );
                    while ($row = fgetcsv($file)) {
                        $index++;
                        
                        
                        if(isset($_POST['update_users']) && sanitize_text_field($_POST['update_users']) === 'true') {
                            $user_id = $flipped_header[$option['ID']];
                            $body['ID'] = $row[$user_id];
                        }
                        

                        if( isset( $option['user_login'] ) && in_array( $option['user_login'], $header ) ) {
                             $user_login = $flipped_header[$option['user_login']];
                             $body['user_login'] = $row[$user_login];
                        }
                        if( isset( $option['user_nicename'] ) && in_array( $option['user_nicename'], $header ) ) {
                            $user_nicename = $flipped_header[$option['user_nicename']];
                            $body['user_nicename'] = $row[$user_nicename];
                        }
                        
                        $user_email = $flipped_header[$option['user_email']];
                        $body['user_email'] = $row[$user_email];
                        

                        if( isset( $option['user_url'] ) && in_array( $option['user_url'], $header ) ) {
                            $user_url = $flipped_header[$option['user_url']];
                            $body['user_url'] = $row[$user_url];
                        }
                        if( isset( $option['user_registered'] ) && in_array( $option['user_registered'], $header ) ) {
                            $user_registered = $flipped_header[$option['user_registered']];
                            $body['user_registered'] = $row[$user_registered];
                        }
                        if( isset( $option['user_activation_key'] ) && in_array( $option['user_activation_key'], $header ) ) {
                            $user_registered = $flipped_header[$option['user_registered']];
                            $body['user_registered'] = $row[$user_registered];
                        }
                        if( isset( $option['user_status'] ) && in_array( $option['user_status'], $header ) ) {
                            $user_status = $flipped_header[$option['user_status']];
                            $body['user_status'] = $row[$user_status];
                        }
                        if( isset( $option['display_name'] ) && in_array( $option['display_name'], $header ) ) {
                            $user_status = $flipped_header[$option['user_status']];
                            $body['user_status'] = $row[$user_status];
                        }
                        if( isset( $option['role'] ) && in_array( $option['role'], $header ) ) {
                            $role = $flipped_header[$option['role']];
                            $body['role'] = $row[$role];
                        } else {
                            $body['role'] = 'subscriber';
                        }
                        if( isset( $option['nickname'] ) && in_array( $option['nickname'], $header ) ) {
                            $nickname = $flipped_header[$option['nickname']];
                            $body['nickname'] = $row[$nickname];
                        }
                        if( isset( $option['first_name'] ) && in_array( $option['first_name'], $header ) ) {
                            $first_name = $flipped_header[$option['first_name']];
                            $body['first_name'] = $row[$first_name];
                        }
                        if( isset( $option['last_name'] ) && in_array( $option['last_name'], $header ) ) {
                            $last_name = $flipped_header[$option['last_name']];
                            $body['last_name'] = $row[$last_name];
                        }
                        if( isset( $option['description'] ) && in_array( $option['description'], $header ) ) {
                            $description = $flipped_header[$option['description']];
                            $body['description'] = $row[$description];
                        }
                        if( isset( $option['rich_editing'] ) && in_array( $option['rich_editing'], $header ) ) {
                            $rich_editing = $flipped_header[$option['rich_editing']];
                            $body['rich_editing'] = $row[$rich_editing];
                        }
                        if( isset( $option['syntax_highlighting'] ) && in_array( $option['syntax_highlighting'], $header ) ) {
                            $syntax_highlighting = $flipped_header[$option['syntax_highlighting']];
                            $body['syntax_highlighting'] = $row[$syntax_highlighting];
                        }
                        if( isset( $option['comment_shortcuts'] ) && in_array( $option['comment_shortcuts'], $header ) ) {
                            $comment_shortcuts = $flipped_header[$option['comment_shortcuts']];
                            $body['comment_shortcuts'] = $row[$comment_shortcuts];
                        }
                        if( isset( $option['use_ssl'] ) && in_array( $option['use_ssl'], $header ) ) {
                            $use_ssl = $flipped_header[$option['use_ssl']];
                            $body['use_ssl'] = $row[$use_ssl];
                        }
                        if( isset( $option['admin_color'] ) && in_array( $option['admin_color'], $header ) ) {
                            $admin_color = $flipped_header[$option['admin_color']];
                            $body['admin_color'] = $row[$admin_color];
                        }
                        if( isset( $option['show_admin_bar_front'] ) && in_array( $option['show_admin_bar_front'], $header ) ) {
                            $show_admin_bar_front = $flipped_header[$option['show_admin_bar_front']];
                            $body['show_admin_bar_front'] = $row[$show_admin_bar_front];
                        }
                        
                        if( sanitize_text_field($_POST['update_users']) === 'false' ) {   
                            if( !email_exists( $body['user_email']  ) ) {
                                $message .= "Imported: User Imported Sucessfully " .  $body['user_email'] . "<br />";
                            } else {
                                $message .= "<span style='color:red'>Skipped: User Email Already Exist " .  $body['user_email'] . "</span><br />";
                            }
                        } else {
                            $message .= "<span style='color: rgb(51 158 237)'>Imported: User Updated Sucessfully " .  $body['user_email'] . "</span><br />";
                        }

                        if( !in_array( $body['role'], array_keys($WP_Roles->role_names) ) ) {
                            wp_send_json( ['status' => true, 'message' => '<span style="color:yellow"> Skipped: '.$body['role'].' Not exist please import the role first.</span>'] );
                        }

                        if(in_array("user_email", array_keys($body))) {
                            
                            wp_insert_user($body);
                        }    
                        
                        if( isset( $option['user_pass'] ) && in_array( $option['user_pass'], $header ) ) {
                                $user_email = $body['user_email'];
                                $user_pass = $flipped_header[$option['user_pass']];
                                $wpdb->update($wpdb->users, array( 'user_pass' => $row[$user_pass] ), array( 'user_email' => $user_email ));
                            }
                        }

                    $response = array(
                        'message' => $message,
                        'status' => true, 
                    );
                    wp_send_json( $response );
                }
            }
        }
    }
    
    public function uiewp_import_roles_caps_callback() {

        $message = '';
        if( $_FILES["file"]["name"] != '' ) {
            $file_arr = explode(".", sanitize_file_name($_FILES["file"]["name"]));
            $file_ext = end($file_arr);

            if(!in_array($_FILES['file']['type'], ['text/csv', 'application/vnd.ms-excel', 'text/comma-separated-values']) && $file_ext != 'csv') {
                wp_send_json(array( 'message' => 'Unsupported Format' ));
                
            } else {
                $uploads_dir = wp_upload_dir()['basedir'] . 'imp-exp-users-wp/imports';
                wp_mkdir_p($uploads_dir);

                $source = $_FILES['file']['tmp_name'];
                $destination = trailingslashit($uploads_dir) . sanitize_file_name($_FILES['file']['name']);
                $status = move_uploaded_file($source, $destination);
                if($status) {

                    $roles = new WP_Roles();

                    $file = fopen($destination, "r");
                    $header = $this->uiewp_get_csv_header($file);

                    if( in_array( "user_login", $header ) && in_array( "user_pass", $header ) ) {
                        wp_send_json( ['status' => true, 'message' => '<span style="color:red">Invalid File</span>'] );
                    }
                    $index = 0;
                    $arr = array();
                    $a = array();
                    $key = 1;
                    while( $row = fgetcsv( $file ) ) {

                        $caps = explode( " ,", $row[3] );

                        $count = count($caps);

                        foreach( $caps as $item ) {
                            $new_caps = explode(":", $item);
                            $capabilities[$new_caps[0]] = $new_caps[1]; 
                        }
                        $body = array(
                            'id'            => $row[0],
                            'role'          => $row[1],
                            'role_name'     => $row[2],
                            'capabilities'  => $capabilities,
                        );

                        if( in_array($body['role'], array_keys($roles->role_names)) ) {
                            remove_role($body['role']);
                            add_role( $body['role'], $body['role_name'], $body['capabilities'] );
                            $message .= "<span style='color:yellow'>Role updated successfully: " . " $row[1] " . " </span><br/ >";
                        } else {
                            $role = add_role( $body['role'], $body['role_name'], $body['capabilities'] );
                            $message .= "Role imported sucessfully";
                        }
                    }
                    
                } else {
                    $message .= "File uploading failed";
                }
            }
        } else {
            $message .= "File not found";
        }
        $response = array(
            'status' => true,
            'message' => $message,
        );
        wp_send_json( $response );
    }

    // Helpers
    private function uiewp_get_files($file) {
        if($file === 'all_users') {

            $file_url = IEUW_EXPORTS . "export-all-users.csv";
            $file_path = IEUW_ROOT_DIR . "exports/export-all-users.csv";

        } else if($file === 'by_roles') {

            $file_url = IEUW_EXPORTS . "export-by-roles.csv";
            $file_path = IEUW_ROOT_DIR . "exports/export-by-roles.csv";

        } else if($file === 'specific_users') {

            $file_url = IEUW_EXPORTS . "export-specific-users.csv";
            $file_path = IEUW_ROOT_DIR . "exports/export-specific-users.csv";

        } else if($file === 'roles_caps') {

            $file_url = IEUW_EXPORTS . "export-role-capabilities.csv";
            $file_path = IEUW_ROOT_DIR . "exports/export-role-capabilities.csv";

        }
        $response = array(
            'file_url' => $file_url,
            'file_path' => $file_path
        );
        return $response;
    }

    private function uiewp_write_csv_header() {
        $option = get_option( 'uiewp_export_field' );

        if(isset( $option ) && is_array( $option )) {
            $headers = array();
            $headers[] = $option['ID'];
            if(isset($option['user_login'])) {
                $headers[] = $option['user_login'];
            }
            if(isset($option['user_pass'])) {
                $headers[] = $option['user_pass'];
            }
            if(isset($option['user_nicename'])) {
                $headers[] = $option['user_nicename'];
            }
            $headers[] = $option['user_email'];
            if(isset($option['user_url'])) {
                $headers[] = $option['user_url'];
            }
            if(isset($option['user_registered'])) {
                $headers[] = $option['user_registered'];
            }
            if(isset($option['user_activation_key'])) {
                $headers[] = $option['user_activation_key'];
            }
            if(isset($option['user_status'])) {
                $headers[] = $option['user_status'];
            }
            if(isset($option['display_name'])) {
                $headers[] = $option['display_name'];
            }
            if(isset($option['role'])) {
                $headers[] = $option['role'];
            }
            if(isset($option['nickname'])) {
                $headers[] = $option['nickname'];
            }
            if(isset($option['first_name'])) {
                $headers[] = $option['first_name'];
            }
            if(isset($option['last_name'])) {
                $headers[] = $option['last_name'];
            }
            if(isset($option['description'])) {
                $headers[] = $option['description'];
            }
            if(isset($option['rich_editing'])) {
                $headers[] = $option['rich_editing'];
            }
            if(isset($option['syntax_highlighting'])) {
                $headers[] = $option['syntax_highlighting'];
            }
            if(isset($option['comment_shortcuts'])) {
                $headers[] = $option['comment_shortcuts'];
            }
            if(isset($option['use_ssl'])) {
                $headers[] = $option['use_ssl'];
            }
            if(isset($option['admin_color'])) {
                $headers[] = $option['admin_color'];
            }
            if(isset($option['show_admin_bar_front'])) {
                $headers[] = $option['show_admin_bar_front'];
            }

        } else {
            $users = get_users(array('all'));
            $fields = $users[0];
            $headers = array_keys((array)$fields->data);
            $headers[] = "roles";
            $headers[] = "nickname";
            $headers[] = "first_name";
            $headers[] = "last_name";
            $headers[] = "description";
            $headers[] = "rich_editing";
            $headers[] = "syntax_highlighting";
            $headers[] = "comment_shortcuts";
            $headers[] = "use_ssl";
            $headers[] = "admin_color";
            $headers[] = "show_admin_bar_front";
        }
        return $headers;
    }

    private function uiewp_get_csv_header($file) {
        $header = fgetcsv($file);
        return $header;
    }

    private function uiewp_export_fields_settings() {
        if(isset( $_POST['export_fields_nonce'] )) {
            $enabled_fields = array();
            $data = array();
            foreach( $_POST as $key => $s_fields ) {
                $data[$key] = sanitize_text_field( $s_fields );
            }
            $enabled_fields['ID'] = $data['ID'];
                
            if (isset($data['user_login_checkbox']) && $data['user_login_checkbox'] == 'on'){
                $enabled_fields['user_login'] = $data['user_login'];
            } else {
                $enabled_fields['user_login_checkbox'] = 'off';
            }
                
            if (isset($data['user_pass_checkbox']) && $data['user_pass_checkbox'] == 'on'){
                $enabled_fields['user_pass'] = $data['user_pass'];
            } else {
                $enabled_fields['user_pass_checkbox'] = 'off';
            }
                
            if (isset($data['user_nicename_checkbox']) && $data['user_nicename_checkbox'] == 'on'){
                $enabled_fields['user_nicename'] = $data['user_nicename'];
            } else {
                $enabled_fields['user_nicename_checkbox'] = 'off';
            }

            $enabled_fields['user_email'] = $data['user_email'];
                
            if (isset($data['user_url_checkbox']) && $data['user_url_checkbox'] == 'on'){
                $enabled_fields['user_url'] = $data['user_url'];
            } else {
                $enabled_fields['user_url_checkbox'] = 'off';
            }
                
            if (isset($data['user_registered_checkbox']) && $data['user_registered_checkbox'] == 'on'){
                $enabled_fields['user_registered'] = $data['user_registered'];
            } else {
                $enabled_fields['user_registered_checkbox'] = 'off';
            }
            
            if (isset($data['user_activation_key_checkbox']) && $data['user_activation_key_checkbox'] == 'on'){
                $enabled_fields['user_activation_key'] = $data['user_activation_key'];
            } else {
                $enabled_fields['user_activation_key_checkbox'] = 'off';
            }
            
            if (isset($data['user_status_checkbox']) && $data['user_status_checkbox'] == 'on'){
                $enabled_fields['user_status'] = $data['user_status'];
            } else {
                $enabled_fields['user_status_checkbox'] = 'off';
            }
                
            if (isset($data['display_name_checkbox']) && $data['display_name_checkbox'] == 'on'){
                $enabled_fields['display_name'] = $data['display_name'];
            } else {
                $enabled_fields['display_name_checkbox'] = 'off';
            }
                
            if (isset($data['role_checkbox']) && $data['role_checkbox'] == 'on'){
                $enabled_fields['role'] = $data['role'];
            } else {
                $enabled_fields['role_checkbox'] = 'off';
            }
            
            if (isset($data['nickname_checkbox']) && $data['nickname_checkbox'] == 'on'){
                $enabled_fields['nickname'] = $data['nickname'];
            } else {
                $enabled_fields['nickname_checkbox'] = 'off';
            }

            if (isset($data['first_name_checkbox']) && $data['first_name_checkbox'] == 'on'){
                $enabled_fields['first_name'] = $data['first_name'];
            } else {
                $enabled_fields['first_name_checkbox'] = 'off';
            }
                
            if (isset($data['last_name_checkbox']) && $data['last_name_checkbox'] == 'on'){
                $enabled_fields['last_name'] = $data['last_name'];
            } else {
                $enabled_fields['last_name_checkbox'] = 'off';
            }
                
            if (isset($data['description_checkbox']) && $data['description_checkbox'] == 'on'){
                $enabled_fields['description'] = $data['description'];
            } else {
                $enabled_fields['description_checkbox'] = 'off';
            }
            
            if (isset($data['rich_editing_checkbox']) && $data['rich_editing_checkbox'] == 'on'){
                $enabled_fields['rich_editing'] = $data['rich_editing'];
            } else {
                $enabled_fields['rich_editing_checkbox'] = 'off';
            }
            
            if (isset($data['syntax_highlighting_checkbox']) && $data['syntax_highlighting_checkbox'] == 'on'){
                $enabled_fields['syntax_highlighting'] = $data['syntax_highlighting'];
            } else {
                $enabled_fields['syntax_highlighting_checkbox'] = 'off';
            }
                
            if (isset($data['comment_shortcuts_checkbox']) && $data['comment_shortcuts_checkbox'] == 'on'){
                $enabled_fields['comment_shortcuts'] = $data['comment_shortcuts'];
            } else {
                $enabled_fields['comment_shortcuts_checkbox'] = 'off';
            }
                
            if (isset($data['use_ssl_checkbox']) && $data['use_ssl_checkbox'] == 'on'){
                $enabled_fields['use_ssl'] = $data['use_ssl'];
            } else {
                $enabled_fields['use_ssl_checkbox'] = 'off';
            }
            
            if (isset($data['admin_color_checkbox']) && $data['admin_color_checkbox'] == 'on'){
                $enabled_fields['admin_color'] = $data['admin_color'];
            } else {
                $enabled_fields['admin_color_checkbox'] = 'off';
            }

            if (isset($data['show_admin_bar_front_checkbox']) && $data['show_admin_bar_front_checkbox'] == 'on'){
                $enabled_fields['show_admin_bar_front'] = $data['show_admin_bar_front'];
            } else {
                $enabled_fields['show_admin_bar_front_checkbox'] = 'off';
            }

            update_option('uiewp_export_field', $enabled_fields);
        }
    }
}