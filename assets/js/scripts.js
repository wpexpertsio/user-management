jQuery(document).ready(function($) {
    // Exporting scripts
    // Initializing select2 on ready 

    $('.wp_users-export').select2({

        placeholder: 'Select users'

    });

    $('.wp_roles-export').select2({

        placeholder: 'Select Roles'

    });

    $("div.select2-field-users").hide();
    $("div.select2-field-roles").hide();

    $("input.select2-search__field").focus(function() {
        if ($("p.error_message").length > 0) {
            $("p.error_message").remove();
        }
    });

    // Button
    $("button#wp_export_users").on("click", function() {

        if (jQuery("input[name='export_all_users']").is(":checked")) {

            export_all_users();

        } else if (jQuery("input[name='export_users_by_roles']").is(":checked")) {

            export_users_by_roles();
        } else if (jQuery("input[name='export_specific_users']").is(":checked")) {

            export_specific_users();

        } else if (jQuery("input[name='export_roles_caps']").is(":checked")) {
            export_roles_caps();

        } else if (!$("input[name='export_all_users']").is(":checked") && !$("input[name='export_users_by_roles']").is(":checked") && !$("input[name='export_specific_users']").is(":checked")) {

            if ($("p.error_message").length !== 1) {

                $("div.error-div").append("<p class='error_message'>Please check an option to proceed exporting.</p>");

            }
        }
    });
    //export all users
    $("input[name='export_all_users']").on("change", function() {

        $("p.error_message").remove();

        if ($(this).is(":checked")) {

            $("div.select2-field-users").fadeOut();
            $("div.roles_checkboxes").fadeOut();
            $("div.select2-field-roles").fadeOut();

            $("input[name='export_users_by_roles']").prop("checked", false);
            $("input[name='export_specific_users']").prop("checked", false);
            $("input[name='export_roles_caps']").prop("checked", false);

        }

    });
    //export users by roles
    $("input[name='export_users_by_roles']").on("change", function() {

        $("p.error_message").remove();

        if ($(this).is(":checked")) {

            $("div.roles_checkboxes").fadeIn();
            $("div.select2-field-users").fadeOut();
            $("div.select2-field-roles").fadeOut();


            $("input[name='export_all_users']").prop("checked", false);
            $("input[name='export_specific_users']").prop("checked", false);
            $("input[name='export_roles_caps']").prop("checked", false);

        } else {

            $("div.roles_checkboxes").fadeOut();

        }

    });
    //export specific users
    $("input[name='export_specific_users']").on("change", function() {

        $("p.error_message").remove();

        if ($(this).is(":checked")) {

            $("div.select2-field-users").fadeIn();
            $("div.roles_checkboxes").fadeOut();
            $("div.select2-field-roles").fadeOut();

            $("input[name='export_all_users']").prop("checked", false);
            $("input[name='export_users_by_roles']").prop("checked", false);
            $("input[name='export_roles_caps']").prop("checked", false);

        } else {

            $("div.select2-field-users").fadeOut();

        }

    });
    // Export roles and caos
    $("input[name='export_roles_caps']").on("change", function() {

        $("p.error_message").remove();

        if ($(this).is(":checked")) {

            $("div.select2-field-roles").fadeIn();
            $("div.roles_checkboxes").fadeOut();
            $("div.select2-field-users").fadeOut();

            $("input[name='export_all_users']").prop("checked", false);
            $("input[name='export_users_by_roles']").prop("checked", false);
            $("input[name='export_specific_users']").prop("checked", false);

        } else {

            $("div.select2-field-roles").fadeOut();

        }

    });

    $("input[name='selected_roles']").on("change", function() {
        $("p.error_message").remove();
    });

});

function get_checkboxes_values() {
    var array = [];
    jQuery("input:checkbox[name='selected_roles']:checked").each(function() {
        array.push(jQuery(this).val());
    });

    if (array.length !== 0) {

        return array;

    } else {

        if (jQuery("p.error_message").length !== 1) {

            jQuery("div.error-div").append("<p class='error_message'>Please select atleast 1 role.</p>");

        }

        return false;

    }
}

function get_select2_dropdown_value_users() {
    var users = jQuery('select[name="users[]"]').val();
    if (users.length !== 0) {
        return users;
    } else {

        if (jQuery("p.error_message").length !== 1) {

            jQuery("div.error-div").append("<p class='error_message'>Please select atleast 1 user.</p>");

        }

        return false;
    }
}

function get_select2_dropdown_value_roles_caps() {
    var roles = jQuery('select[name="roles[]"]').val();
    return roles;
}

function export_all_users() {
    jQuery("div.loader").css('display', 'flex');
    jQuery("button#wp_export_users").attr('disabled', true);
    jQuery("button#wp_export_users").text('Exporting...');
    data = {
        'action': 'export_all_users_ajax'
    };
    jQuery.post(ajaxURL, data, function(response) {
        if (response) {
            window.location.href = response.file_url;
            jQuery('div.error-div').append('<p id="success-export" style="color:green;">' + response.count + '</p>');
            setTimeout(function() {

                jQuery("p#success-export").remove();

            }, 5000);
        }
        jQuery("button#wp_export_users").attr('disabled', false);
        jQuery("#wp_export_users").text('Export');
        jQuery("div.loader").css('display', 'none');
    });
}

function export_users_by_roles() {
    const roles = get_checkboxes_values();
    if (roles !== false) {
        jQuery("div.loader").css('display', 'flex');
        jQuery("button#wp_export_users").attr('disabled', true);
        jQuery("button#wp_export_users").text('Exporting...');
        data = {
            'roles': roles,
            'action': 'export_users_by_roles'
        }
        jQuery.post(ajaxURL, data, function(response) {
            if (response) {
                window.location.href = response.file_url;
                jQuery('div.error-div').append('<p id="success-export" style="color:green;">' + response.count + '</p>');
                setTimeout(function() {

                    jQuery("p#success-export").remove();

                }, 5000);
            }
            jQuery("button#wp_export_users").attr('disabled', false);
            jQuery("button#wp_export_users").text('Export');
            jQuery("div.loader").css('display', 'none');

        });
    }

}

function export_specific_users() {
    const users = get_select2_dropdown_value_users();
    if (users != false) {
        jQuery("div.loader").css('display', 'flex');
        jQuery("button#wp_export_users").attr('disabled', true);
        jQuery("button#wp_export_users").text('Exporting...');
        data = {
            'users': users,
            'action': 'export_specific_users'
        }
        jQuery.post(ajaxURL, data, function(response) {
            if (response) {
                window.location.href = response.file_url;
                jQuery('div.error-div').append('<p id="success-export" style="color:green;">' + response.count + '</p>');
                setTimeout(function() {

                    jQuery("p#success-export").remove();

                }, 5000);
            }
            jQuery("button#wp_export_users").attr('disabled', false);
            jQuery("button#wp_export_users").text('Export');
            jQuery("div.loader").css('display', 'none');

        });
    }
}

function export_roles_caps() {
    var roles = get_select2_dropdown_value_roles_caps();
    jQuery("div.loader").css('display', 'flex');
    jQuery("button#wp_export_users").attr('disabled', true);
    jQuery("button#wp_export_users").text('Exporting...');
    data = {
        'roles': roles,
        'action': 'export_roles_caps'
    }
    jQuery.post(ajaxURL, data, function(response) {
        if (response) {
            window.location.href = response.file_url;
            jQuery('div.error-div').append('<p id="success-export" style="color:green;">' + response.count + '</p>');
            setTimeout(function() {

                jQuery("p#success-export").remove();

            }, 5000);
        }
        jQuery("button#wp_export_users").attr('disabled', false);
        jQuery("button#wp_export_users").text('Export');
        jQuery("div.loader").css('display', 'none');

    });
}

// Input type File 
var url = window.location.href;
var arr = url.split("&");
if (arr.length > 1 && arr[1] === 'tab=import') {
    document.querySelector("html").classList.add('js');

    var fileInput = document.querySelector(".input-file"),
        button = document.querySelector(".input-file-trigger"),
        the_return = document.querySelector(".file-return");

    button.addEventListener("keydown", function(event) {
        if (event.keyCode == 13 || event.keyCode == 32) {
            fileInput.focus();
        }
    });
    button.addEventListener("click", function(event) {
        fileInput.focus();
        return false;
    });
    fileInput.addEventListener("change", function(event) {
        var scanImagePath = this.value;
        choosedFileName = scanImagePath.substring(scanImagePath.lastIndexOf("\\") + 1, scanImagePath.length);
        the_return.innerHTML = choosedFileName.substring() + "<button type='button' title='Remove File' style='text-decoration:none;margin-left: 2%;color: rgb(255 0 0);' id='remove_import_file'>X</button>";
    });
}

jQuery("body").on('click', 'button#remove_import_file', function(event) {
	event.preventDefault();
	remove_import_file();
});

jQuery(".input-file-trigger").click(function() {
        if (("div.error-div-imp").length) {
            jQuery("div.error-div-imp").remove();
        }
    })
    // Importing Scripts

jQuery(document).ready(function($) {

    $("button#wp_import_users_roles").on('click', function(e) {
        e.preventDefault();
        if ($("input[name='import_users']").is(":checked")) {
            import_users();
        } else if ($("input[name='import_roles_caps']").is(":checked")) {
            import_roles_caps();
        }


        if (!$("input[name='import_users']").is(":checked") && !$("input[name='import_roles_caps']").is(":checked")) {
            if ($("p.error_message").length !== 1) {
                $("div.error-div-imp").append("<p class='error_message'>Please check an option to proceed importing.</p>");
            }
        }

    });


    $("input#file-to-import").on('click', function(e) {
        if (!$("input[name='import_users']").is(":checked") && !$("input[name='import_roles_caps']").is(":checked")) {
            e.preventDefault();
            if ($("p.error_message").length !== 1) {
                $("div.error-div-imp").append("<p class='error_message'>Please check an option to proceed importing.</p>");

            }
        }
    });

    $("input[name='import_users']").on("change", function() {
        $("p.error_message").hide();
        if ($(this).is(":checked")) {
            $("button#wp_import_users_roles").text("Import Users ");
            $("input[name='import_roles_caps']").prop("checked", false);
            $("div#import_update_users").fadeIn();
        } else {
            $("input[name='import_update_users']").prop("checked", false);
            $("input[name='import_roles_caps']").prop("checked", true);
            $("div#import_update_users").fadeOut();

        }
    });

    $("input[name='import_roles_caps']").on("change", function() {
        $("p.error_message").hide();
        if ($(this).is(":checked")) {
            jQuery("button#wp_import_users_roles").text("Import Roles ");
            $("input[name='import_users']").prop("checked", false);
            $("div#import_update_users").fadeOut();

        } else {
            $("div#import_update_users").fadeIn();
            $("input[name='import_users']").prop("checked", true);
        }
    });

});

function import_users() {
    var file = get_file();
    if (file !== false) {

        jQuery("div.flexbox").css('display', 'flex');
        jQuery("button#wp_import_users_roles").attr('disabled', true);
        jQuery("button#wp_import_users_roles").text('Importing Users ...');
        var update_users = jQuery('input[name="import_update_users"]').is(":checked");
        var formData = new FormData();
        formData.append("update_users", update_users);
        formData.append("file", file);
        formData.append("action", "import_users");
        jQuery.ajax({
            url: ajaxURL,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response, textStatus, jqXHR) {
                if (response.status === true) {
                    jQuery("div.wrap").append('<div class="error-div-imp"></div>');
                    jQuery("html, body").animate({ scrollTop: document.body.scrollHeight }, "slow");
                    jQuery('div.error-div-imp').append('<h2>Status</h2><p id="success-import" style="color:green;">' + response.message + '</p>');
                }
                jQuery("div.flexbox").css('display', 'none');
                jQuery("button#wp_import_users_roles").attr('disabled', false);
                jQuery("button#wp_import_users_roles").text('Import Users');
                document.getElementById("file-to-import").value = null;
                jQuery("p.file-return").text('');

            }
        });
    }
}

function import_roles_caps() {
    var file = get_file();
    if (file !== false) {
        jQuery("div.flexbox").css('display', 'flex');
        jQuery("button#wp_import_users_roles").attr('disabled', true);
        jQuery("button#wp_import_users_roles").text('Importing Roles ...');
        var formData = new FormData();
        formData.append("file", file);
        formData.append("action", "import_roles_caps");

        jQuery.ajax({
            url: ajaxURL,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response, textStatus, jqXHR) {
                if (response.status === true) {
                    jQuery("div.wrap").append('<div class="error-div-imp"></div>');
                    jQuery("html, body").animate({ scrollTop: document.body.scrollHeight }, "slow");
                    jQuery('div.error-div-imp').append('<h2>Status</h2><p id="success-import" style="color:green;">' + response.message + '</p>');
                }
                jQuery("div.flexbox").css('display', 'none');
                jQuery("button#wp_import_users_roles").attr('disabled', false);
                jQuery("button#wp_import_users_roles").text('Import Roles');
                document.getElementById("file-to-import").value = null;
                jQuery("p.file-return").text('');

            }
        });

    }
}

function get_file() {
    var file = document.getElementById("file-to-import").files[0];
    if (file !== undefined) {
        var file_name = file.name;
        var file_ext = file_name.split(".").pop().toLowerCase();
        if (jQuery.inArray(file_ext, ['csv']) == -1) {
            alert("Invalid file type");
            return false;
        }
        return file;
    } else {
        alert('Please select file');
    }
    return false;
}

function remove_import_file() {
	jQuery("input#file-to-import").val("");
	jQuery(".file-return").text("");
}