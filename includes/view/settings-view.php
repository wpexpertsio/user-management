<?php $option = get_option( 'uiewp_export_field' ); ?>

<div class="uiewp-settings-container">
    <h2><?php echo __('Select Fields For Export', IEUW_TEXT_DOMAIN); ?></h2>
    <div class="wrap">
        <form action="model.php" name="fields_for_export" method="post">
        <input type="hidden" name="export_fields_nonce" value="uiew_export_user_settings_field">
            <div class="uiewp-settings-form-group">
                <label class="uiewp-label-field" for=""><?php echo __('USER_ID', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(229 20 20);"> <?php echo __('(Required)', IEUW_TEXT_DOMAIN); ?></span></label> 
                <div style="margin-left:30px;" class="field">
                   <input type="text" id="uiewp-input-field" placeholder="ID" name="ID" value="<?php echo isset($option['ID']) ? esc_attr($option['ID']) : 'ID' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="user_login_checkbox" <?php echo (isset($option['user_login_checkbox']) && $option['user_login_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('user_login', IEUW_TEXT_DOMAIN); ?></label>
                <div class="field">
                    <input type="text" id="uiewp-input-field" placeholder="user_login" name="user_login" value="<?php echo isset($option['user_login']) ? esc_attr($option['user_login']) : 'user_login' ?>">
                    <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="user_pass_checkbox" <?php echo (isset($option['user_pass_checkbox']) && $option['user_pass_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('user_pass', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(37 167 15);"> <?php echo __('(Encrypted)', IEUW_TEXT_DOMAIN); ?></span></label> 
                <div class="field">
                   <input type="text" id="uiewp-input-field" placeholder="user_pass" name="user_pass" value="<?php echo isset($option['user_pass']) ? esc_attr($option['user_pass']) : 'user_pass' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="user_nicename_checkbox" <?php echo (isset($option['user_nicename_checkbox']) && $option['user_nicename_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('user_nicename', IEUW_TEXT_DOMAIN); ?></label>
                <div class="field">
                    <input type="text" id="uiewp-input-field" placeholder="user_nicename" name="user_nicename" value="<?php echo isset($option['user_nicename']) ? esc_attr($option['user_nicename']) : 'user_nicename' ?>">
                    <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="uiewp-label-field" for=""><?php echo __('USER_EMAIL', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(229 20 20);"> <?php echo __('(Required)', IEUW_TEXT_DOMAIN); ?></span></label> 
                <div class="field" style="margin-left:30px;">
                   <input type="text" id="uiewp-input-field" placeholder="user_email" name="user_email" value="<?php echo isset($option['user_email']) ? esc_attr($option['user_email']) : 'user_email' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="user_url_checkbox" <?php echo (isset($option['user_url_checkbox']) && $option['user_url_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('user_url', IEUW_TEXT_DOMAIN); ?></label>
                <div class="field">
                    <input type="text" id="uiewp-input-field" placeholder="user_url" name="user_url" value="<?php echo isset($option['user_url']) ? esc_attr($option['user_url']) : 'user_url' ?>">
                    <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="user_registered_checkbox" <?php echo (isset($option['user_registered_checkbox']) && $option['user_registered_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('user_registered', IEUW_TEXT_DOMAIN); ?></label> 
                <div class="field">
                   <input type="text" id="uiewp-input-field" placeholder="user_registered" name="user_registered" value="<?php echo isset($option['user_registered']) ? esc_attr($option['user_registered']) : 'user_registered' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="user_activation_key_checkbox" <?php echo (isset($option['user_activation_key_checkbox']) && $option['user_activation_key_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('user_activation_key', IEUW_TEXT_DOMAIN); ?></label>
                <div class="field">
                    <input type="text" id="uiewp-input-field" placeholder="user_activation_key" name="user_activation_key" value="<?php echo isset($option['user_activation_key']) ? esc_attr($option['user_activation_key']) : 'user_activation_key' ?>">
                    <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="user_status_checkbox" <?php echo (isset($option['user_status_checkbox']) && $option['user_status_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('user_status', IEUW_TEXT_DOMAIN); ?></label> 
                <div class="field">
                   <input type="text" id="uiewp-input-field" placeholder="user_status" name="user_status" value="<?php echo isset($option['user_status']) ? esc_attr($option['user_status']) : 'user_status' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="display_name_checkbox" <?php echo (isset($option['display_name_checkbox']) && $option['display_name_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('display_name', IEUW_TEXT_DOMAIN); ?></label>
                <div class="field">
                    <input type="text" id="uiewp-input-field" placeholder="display_name" name="display_name" value="<?php echo isset($option['display_name']) ? esc_attr($option['display_name']) : 'display_name' ?>">
                    <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="role_checkbox" <?php echo (isset($option['role_checkbox']) && $option['role_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('role', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label> 
                <div class="field">
                   <input type="text" id="uiewp-input-field" placeholder="role" name="role" value="<?php echo isset($option['role']) ? esc_attr($option['role']) : 'role' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="nickname_checkbox" <?php echo (isset($option['nickname_checkbox']) && $option['nickname_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('nickname', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label>
                <div class="field">
                    <input type="text" id="uiewp-input-field" placeholder="nickname" name="nickname" value="<?php echo isset($option['nickname']) ? esc_attr($option['nickname']) : 'nickname' ?>">
                    <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="first_name_checkbox" <?php echo (isset($option['first_name_checkbox']) && $option['first_name_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('first_name', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label> 
                <div class="field">
                   <input type="text" id="uiewp-input-field" placeholder="first_name" name="first_name" value="<?php echo isset($option['first_name']) ? esc_attr($option['first_name']) : 'first_name' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="last_name_checkbox" <?php echo (isset($option['last_name_checkbox']) && $option['last_name_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('last_name', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label>
                <div class="field">
                    <input type="text" id="uiewp-input-field" placeholder="last_name" name="last_name" value="<?php echo isset($option['last_name']) ? esc_attr($option['last_name']) : 'last_name' ?>">
                    <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="description_checkbox" <?php echo (isset($option['description_checkbox']) && $option['description_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('description', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label> 
                <div class="field">
                   <input type="text" id="uiewp-input-field" placeholder="description" name="description" value="<?php echo isset($option['description']) ? esc_attr($option['description']) : 'description' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="rich_editing_checkbox" <?php echo (isset($option['rich_editing_checkbox']) && $option['rich_editing_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('rich_editing', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label>
                <div class="field">
                    <input type="text" id="uiewp-input-field" placeholder="rich_editing" name="rich_editing" value="<?php echo isset($option['rich_editing']) ? esc_attr($option['rich_editing']) : 'rich_editing' ?>">
                    <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="syntax_highlighting_checkbox" <?php echo (isset($option['syntax_highlighting_checkbox']) && $option['syntax_highlighting_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('syntax_highlighting', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label> 
                <div class="field">
                   <input type="text" id="uiewp-input-field" placeholder="syntax_highlighting" name="syntax_highlighting" value="<?php echo isset($option['syntax_highlighting']) ? esc_attr($option['syntax_highlighting']) : 'syntax_highlighting' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="comment_shortcuts_checkbox" <?php echo (isset($option['comment_shortcuts_checkbox']) && $option['comment_shortcuts_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('comment_shortcuts', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label>
                <div class="field">
                    <input type="text" id="uiewp-input-field" placeholder="comment_shortcuts" name="comment_shortcuts" value="<?php echo isset($option['comment_shortcuts']) ? esc_attr($option['comment_shortcuts']) : 'comment_shortcuts' ?>">
                    <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="use_ssl_checkbox" <?php echo (isset($option['use_ssl_checkbox']) && $option['use_ssl_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('use_ssl', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label> 
                <div class="field">
                   <input type="text" id="uiewp-input-field" placeholder="use_ssl" name="use_ssl" value="<?php echo isset($option['use_ssl']) ? esc_attr($option['use_ssl']) : 'use_ssl' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="admin_color_checkbox" <?php echo (isset($option['admin_color_checkbox']) && $option['admin_color_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('admin_color', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label>
                <div class="field">
                    <input type="text" id="uiewp-input-field" placeholder="admin_color" name="admin_color" value="<?php echo isset($option['admin_color']) ? esc_attr($option['admin_color']) : 'admin_color' ?>">
                    <div class="line"></div>
                </div>
            </div>
            <div class="uiewp-settings-form-group">
                <label class="switch" id="uiewp-switch"><input type="checkbox" name="show_admin_bar_front_checkbox" <?php echo (isset($option['show_admin_bar_front_checkbox']) && $option['show_admin_bar_front_checkbox'] == 'off') ? '' : 'checked' ?>><span class="slider round fields_checkbox"></span></label>
                <label class="uiewp-label-field" for=""><?php echo __('show_admin_bar_front', IEUW_TEXT_DOMAIN); ?> <span class="user_pass-enc" style="color: rgb(3 139 176);"> <?php echo __('(Meta)', IEUW_TEXT_DOMAIN); ?></span></label> 
                <div class="field">
                   <input type="text" id="uiewp-input-field" placeholder="show_admin_bar_front" name="show_admin_bar_front" value="<?php echo isset($option['show_admin_bar_front']) ? esc_attr($option['show_admin_bar_front']) : 'show_admin_bar_front' ?>">
                   <div class="line"></div>
                </div>
            </div>
            <button class="btn uiewp_export_fields"  type="submit">Save Changes</button>
        </form>
    </div>

</div>

<style>




</style>