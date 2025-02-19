<?php

/**
 * Provide a admin area view for the plugin
 *
 * @link       https://www.facebook.com/razpeel
 *
 * @package    acc_user_importer
 * @subpackage acc_user_importer/admin/partials
 */

/*
 * List menu page in the Wordpress admin.
 */
add_action("admin_menu", "accUM_add_menu_page");
function accUM_add_menu_page()
{
    add_users_page(
        "ACC Administration", //Title
        "ACC Admin", //Menu Title
        "edit_users", //Capability
        "acc_admin_page", //Slug
        "accUM_render_options_pages" //Callback
    );
    add_options_page(
        "ACC Email Templates", //Title
        "ACC Email Templates", //Menu Title
        "edit_users", //Capability
        "email_templates", //Slug
        "acc_email_settings" //Callback
    );
}

/*
 * Render theme options pages.
 */
function accUM_render_options_pages()
{
    require plugin_dir_path(__FILE__) . "/acc_user_importer-admin-display.php";
    require_once ACC_BASE_DIR . "/template/cron_settings.php";
    require_once ACC_BASE_DIR . "/template/acc_logs.php";
}

function acc_email_settings()
{
    require_once ACC_BASE_DIR . "/template/email_settings.php";
}

// Define functions to get default values from different files.
function accUM_get_login_name_mapping_default()
{
    return "member_number";
}
function accUM_get_section_default()
{
    return "Ottawa";
}
function accUM_get_new_user_role_action_default()
{
    return "set_role";
}
function accUM_get_new_user_role_value_default()
{
    return "subscriber";
}
function accUM_get_default_notif_title()
{
    return "ACC membership change notification";
}
function accUM_get_ex_user_role_action_default()
{
    return "set_role";
}
function accUM_transition_from_contactID_default()
{
    return "off";
}
function accUM_readonly_mode_default()
{
    return "off";
}
function accUM_verify_expiry_default()
{
    return "off";
}
function accUM_get_delete_ex_users_default()
{
    return "off";
}
function accUM_get_ex_user_role_value_default()
{
    return "subscriber";
}
function accUM_get_when_2_delete_ex_user_default()
{
    return 365;
}
function accUM_get_new_owner_default()
{
    return "";
}
function accUM_get_default_max_log_files()
{
    return 500;
}
function accUM_get_notification_emails_default()
{
    return "";
}
function accUM_get_sync_list_default()
{
    return "";
}

// Get the section name as per the settings
function accUM_getSectionName()
{
    $options = get_option("accUM_data");
    if (!isset($options["accUM_section_api_id"])) {
        $sectionName = accUM_get_section_default();
    } else {
        $sectionName = $options["accUM_section_api_id"];
    }
    return $sectionName;
}

// Returns true if the database is transitioning from FromContactID usernames.
function accUM_get_transitionFromContactID()
{
    $options = get_option("accUM_data");
    if (!isset($options["accUM_transition_from_contactID"])) {
        $transitionFromContactID = accUM_transition_from_contactID_default();
    } else {
        $transitionFromContactID = $options["accUM_transition_from_contactID"];
    }
    return $transitionFromContactID == "on";
}

// Returns true if the plugin operates in read-only mode (for debug)
function accUM_get_readonly_mode()
{
    $options = get_option("accUM_data");
    if (!isset($options["accUM_readonly_mode"])) {
        $readonly_mode = accUM_readonly_mode_default();
    } else {
        $readonly_mode = $options["accUM_readonly_mode"];
    }
    return $readonly_mode == "on";
}

// Returns true if we need to scan the DB looking for expired users
function accUM_get_verify_expiry()
{
    $options = get_option("accUM_data");
    if (!isset($options["accUM_verify_expiry"])) {
        $setting = accUM_verify_expiry_default();
    } else {
        $setting = $options["accUM_verify_expiry"];
    }
    return $setting == "on";
}

// Returns true if we need to delete old expired users from database
function accUM_get_delete_ex_users()
{
    $options = get_option("accUM_data");
    if (!isset($options["accUM_delete_ex_users"])) {
        $setting = accUM_get_delete_ex_users_default();
    } else {
        $setting = $options["accUM_delete_ex_users"];
    }
    return $setting == "on";
}

// Returns the configured list of users to synchronize
function accUM_get_sync_list()
{
    $options = get_option("accUM_data");
    if (!isset($options["accUM_sync_list"])) {
        $setting = accUM_get_sync_list_default();
    } else {
        $setting = $options["accUM_sync_list"];
    }
    return $setting;
}

// Returns the number of days before deleting an expired user.
function accUM_get_when_2_delete_ex_user()
{
    $options = get_option("accUM_data");
    if (!isset($options["accUM_when_2_delete_ex_user"])) {
        $setting = accUM_get_when_2_delete_ex_user_default();
    } else {
        $setting = $options["accUM_when_2_delete_ex_user"];
    }
    return $setting;
}

// Returns the new content owner when a user is deletec.
function accUM_get_new_owner()
{
    $options = get_option("accUM_data");
    if (!isset($options["accUM_new_owner"])) {
        $setting = accUM_get_new_owner_default();
    } else {
        $setting = $options["accUM_new_owner"];
    }
    return $setting;
}

/*
 * Register user settings for options page.
 */
add_action("admin_init", "accUM_settings_init");
function accUM_settings_init()
{
    //define sections
    add_settings_section(
        "accUM_user_section",
        "User Settings",
        "",
        "acc_admin_page"
    );

    add_settings_field(
        "accUM_section_api_id", //ID
        "Section for which to import membership", //Title
        "accUM_select_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "name" => "accUM_section_api_id",
            "values" => [
                "SQUAMISH" => "SQUAMISH",
                "CALGARY" => "CALGARY",
                "OTTAWA" => "OTTAWA",
                "MONTRÉAL" => "MONTRÉAL",
                "OUTAOUAIS" => "OUTAOUAIS",
                "VANCOUVER" => "VANCOUVER",
                "ROCKY MOUNTAIN" => "ROCKY MOUNTAIN",
                "EDMONTON" => "EDMONTON",
                "TORONTO" => "TORONTO",
                "YUKON" => "YUKON",
                "BUGABOOS" => "BUGABOOS",
            ],
            "default" => accUM_get_section_default(),
            "help" => "Select one",
        ]
    );

    add_settings_field(
        "accUM_token", //ID
        "One or more section authentication tokens. Section names are in Capitals. " .
            "Example with bogus token values: " .
            "OUTAOUAIS:K39FKJ5HJDU2,MONTRÉAL:K49G86J345",
        "accUM_text_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "type" => "password",
            "name" => "accUM_token",
            "html_tags" => "required",
        ]
    );

    add_settings_field(
        "accUM_since_date", //ID
        "Sync changes since when? This normally shows the last run time (in UTC), " .
            "but you can force a date in ISO 8601 format such as 2020-11-23T15:05:00.",
        "accUM_text_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "type" => "text",
            "name" => "accUM_since_date",
            "help" =>
                "The date gets updated when the plugin runs automatically, " .
                "but not when it runs manually with the Update button",
        ]
    );

    add_settings_field(
        "accUM_sync_list", //ID
        "Only sync this comma-separated list of ACC member numbers",
        "accUM_text_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "type" => "text",
            "name" => "accUM_sync_list",
            "default" => accUM_get_sync_list_default(),
            "help" =>
                "Normally blank. Enter member numbers to manually sync those members " .
                "using the Update button. Dont forget to clear the box afterward to " .
                "ensure normal automatic sync.",
        ]
    );

    add_settings_field(
        "accUM_login_name_mapping", //ID
        "Set usernames to (Use with caution, this affects login of users, " .
            "although they always can login using their email)",
        "accUM_select_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "name" => "accUM_login_name_mapping",
            "values" => [
                "member_number" => "ACC member number",
                "Firstname Lastname" => "Firstname Lastname",
            ],
            "default" => accUM_get_login_name_mapping_default(),
        ]
    );

    add_settings_field(
        "accUM_transition_from_contactID", //ID
        "Usernames will transition from ContactID to Interpodia member_number? " .
            "Check this box for a safer transition (verifies that member being synced has the right name)",
        "accUM_chkbox_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "name" => "accUM_transition_from_contactID",
            "default" => accUM_transition_from_contactID_default(),
        ]
    );

    add_settings_field(
        "accUM_readonly_mode", //ID
        "Test mode: do not update Wordpress database. " .
            "Check this box to do a normal run but skip the Wordpress users update.",
        "accUM_chkbox_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "name" => "accUM_readonly_mode",
            "default" => accUM_readonly_mode_default(),
        ]
    );

    add_settings_field(
        "accUM_new_user_role_action", //ID
        "When creating a new user, what should I do with role?",
        "accUM_select_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "name" => "accUM_new_user_role_action",
            "values" => [
                "set_role" => "Set role",
                "add_role" => "Add role",
                "nc" => "Do not change role",
            ],
            "default" => accUM_get_new_user_role_action_default(),
        ]
    );

    $roles = wp_roles()->get_names();
    add_settings_field(
        "accUM_new_user_role_value", //ID
        "role value?", //Title
        "accUM_select_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "name" => "accUM_new_user_role_value",
            "values" => $roles,
            "default" => accUM_get_new_user_role_value_default(),
        ]
    );

    add_settings_field(
        "accUM_ex_user_role_action", //ID
        "When expiring a user, what should I do with role?",
        "accUM_select_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "name" => "accUM_ex_user_role_action",
            "values" => [
                "set_role" => "Set role",
                "remove_role" => "Remove role",
                "nc" => "Do not change role",
            ],
            "default" => accUM_get_ex_user_role_action_default(),
        ]
    );

    add_settings_field(
        "accUM_ex_user_role_value", //ID
        "role value?", //Title
        "accUM_select_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "name" => "accUM_ex_user_role_value",
            "values" => $roles,
            "default" => accUM_get_ex_user_role_value_default(),
        ]
    );

    add_settings_field(
        "accUM_verify_expiry", //ID
        "Also check user expiry in local DB",
        "accUM_chkbox_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "name" => "accUM_verify_expiry",
            "default" => accUM_verify_expiry_default(),
        ]
    );

    add_settings_field(
        "accUM_delete_ex_users", //ID
        "Delete expired user accounts after a while",
        "accUM_chkbox_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "name" => "accUM_delete_ex_users",
            "default" => accUM_get_delete_ex_users_default(),
            "help" => "Requires 'Also check user expiry' option.",
        ]
    );

    add_settings_field(
        "accUM_when_2_delete_ex_user", //ID
        "How many days before deleting expired users from database?",
        "accUM_text_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "type" => "number",
            "name" => "accUM_when_2_delete_ex_user",
            "default" => accUM_get_when_2_delete_ex_user_default(),
            "help" =>
                "Enter the number of days after which to delete the user account.",
        ]
    );

    add_settings_field(
        "accUM_new_owner", //ID
        "When deleting a user, who will become the new content owner?",
        "accUM_text_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "type" => "text",
            "name" => "accUM_new_owner",
            "default" => accUM_get_new_owner_default(),
            "help" =>
                "Enter the new owner login name. Suggestion: manually " .
                "create a dummy user (example: 'ex-member') to receive " .
                "ownership of content for users we need to delete, " .
                "and enter its login name here. The plugin will reassign " .
                "posts, pages, articles, events. Leaving this box " .
                "empty will delete the user content along with the user, " .
                "and you might end up with missing pages or broken links.",
        ]
    );

    add_settings_field(
        "accUM_notification_emails", //ID
        "Admin to notify about membership creation/expiry? List of emails, comma separated. Leave blank for no notifications",
        "accUM_text_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "type" => "text",
            "name" => "accUM_notification_emails",
            "default" => accUM_get_notification_emails_default(),
        ]
    );

    add_settings_field(
        "accUM_notification_title", //ID
        "Title of admin notification email",
        "accUM_text_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "type" => "text",
            "name" => "accUM_notification_title",
            "default" => accUM_get_default_notif_title(),
        ]
    );

    add_settings_field(
        "accUM_max_log_files", //ID
        "Maximum number of log files to keep",
        "accUM_text_render", //Callback
        "acc_admin_page", //Page
        "accUM_user_section", //Section
        [
            "type" => "number",
            "name" => "accUM_max_log_files",
            "default" => accUM_get_default_max_log_files(),
        ]
    );

    //Register the array that will store all plugin data
    register_setting("acc_admin_page", "accUM_data", "accUM_sanitize_data");
}

/*
 * Render the textbox fields.
 */
function accUM_text_render($args)
{
    $options = get_option("accUM_data");
    $input_name = $args["name"];
    $input_type = $args["type"];
    if (empty($options[$input_name])) {
        $input_value = $args["default"];
    } else {
        $input_value = $options[$input_name];
    }

    $html = "<input type=\"$input_type\"";
    $html .= " id=\"$input_name\"";
    $html .= " name=\"accUM_data[$input_name]\"";

    //if memory is empty and there is a defauly, use that
    if (empty($input_value) && $args["default"]) {
        $input_value = $args["default"];
    }

    //add extra html tags if any are given
    if (!empty($args["html_tags"])) {
        $html .= " " . $args["html_tags"];
    }

    $html .= " value=\"$input_value\"";

    //if there is help text to display when hovering
    if (!empty($args["help"])) {
        $help = $args["help"];
        $html .= " title=\"$help\"";
    }

    $html .= "/>";

    echo $html;
}

function accUM_select_render($args)
{
    $options = get_option("accUM_data");
    $input_name = $args["name"];
    if (empty($options[$input_name])) {
        $select_value = $args["default"];
    } else {
        $select_value = $options[$input_name];
    }

    //if there is help text to display when hovering
    $help = "";
    if (!empty($args["help"])) {
        $help = $args["help"];
    }

    $html = "<select id=\"$input_name\" name=\"accUM_data[$input_name] \" title=\"$help\">";

    //Fill columns
    if ($args["values"]) {
        foreach ($args["values"] as $key => $value) {
            $html .= "<option value=\"$key\"";
            if ($key == $select_value) {
                $html .= ' selected="selected"';
            }
            $html .= ">$value";
            $html .= "</option>";
        }
    }
    echo $html . "</select>";
}

/*
 * Render for a single on/off checkbox.
 * If checked, the WP database stores 'on'.
 * If not checked, the WP database has no data for that option.
 */
function accUM_chkbox_render($args)
{
    $options = get_option("accUM_data");
    $input_name = $args["name"];
    if (empty($options[$input_name])) {
        $select_value = $args["default"];
    } else {
        $select_value = $options[$input_name];
    }

    $html = "<input type=\"checkbox\"";
    $html .= " id=\"$input_name\"";
    $html .= " name=\"accUM_data[$input_name]\"";

    //if there is help text to display when hovering
    if (!empty($args["help"])) {
        $help = $args["help"];
        $html .= " title=\"$help\"";
    }

    $html .= checked("on", $select_value, false) . " />";
    echo $html;
}

/*
 * WIP: Sanitize and update post data after submit.
 */
function accUM_sanitize_data($options)
{
    foreach ($options as $key => $val) {
        $options[$key] = sanitize_text_field($val);
    }
    return $options;
}

?>
