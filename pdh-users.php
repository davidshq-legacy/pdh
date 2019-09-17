<?php
// Users: https://developer.wordpress.org/plugins/users/
// Stored in users table
// Users are assigned roles and roles have capabilities.
// Principal of Least Privileges: Give a user only the privileges that are essential for performing the desired work.

// Working with Users: https://developer.wordpress.org/plugins/users/working-with-users/

// Adding Users
// wp_create_user = creates user with username, password, and email parameters
// https://developer.wordpress.org/reference/functions/wp_create_user/
// wp_create_user( string $username, string $password, string $email = '' );
// Uses wp_slash() to escape values: https://developer.wordpress.org/reference/functions/wp_slash/
// Uses PHP's compact() to create an array with these values
// Uses wp_insert_user() to perform insert operation

// check if username is taken
$user_id = username_exists($user_name);

// check that the email address is not already registered
if (!$user_id && email_exists($user_email) === false) {
    // create a random password
    $random_password = wp_generate_password(
        $length = 12,
        $include_standard_special_chars = false
    );

    // create the user
    $user_id = wp_create_user( $username, $random_password, $user_email );
}

// wp_insert_user = accepts an array or object describing the user and it's properties
// https://developer.wordpress.org/reference/functions/wp_insert_user/
// wp_insert_user( array|object|WP_User $userdata );
// This function calls a filter for most predefined properties - user_register when creating a user, profile_update when updating a user
$username = $_POST['username'];
$password = $_POST['password'];
$website = $_POST['website'];
$user_data = [
    'user_login'    => $username,
    'user_pass'     => $password,
    'user_url'      => $website,
];

$user_id = wp_insert_user($user_data);

// success
if (!is_wp_error($user_id)) {
    echo 'User created: ' . $user_id;
}

// Update User
wp_update_user(
    mixed $userdata
);

// Update Single Piece of Metadata for User: https://developer.wordpress.org/reference/functions/update_user_meta/
$user_id = 1;
$website = 'https://somewhere.com';

$user_id = wp_update_user(
    [
        'ID'        => $user_id,
        'user_url'  => $website,
    ]
    );

if (is_wp_error($user_id)) {
    // error
} else {
    // success
}

// Delete User: https://developer.wordpress.org/reference/functions/wp_delete_user/
// Note: Unless you reassign the entities associated with a user you are deleting to another user, all unassociated entities will be deleted as well
wp_delete_user(
    int $id.
    int $reassign = null
);

// Working with User Metadata: https://developer.wordpress.org/plugins/users/working-with-user-metadata/
// users table is very basic, most data about user will need to be saved elsewhere
// Users Table: ID, user_login, user_pass, user_nicename, user_email, user_url, user_registered, user_activation_key, user_status, display_name
// Instead we'll add to usermeta table

// Modifying User Meta via a Form Field on the User's Profile Screen
// edit_user_profile hook = fired whenever a user edits their own profile.
// show_user_profile hook = fired whenever a user edits someone else's profile.
/** The field on the editing screens.
 * 
 * @param $user WP_User user object
 */
function pdh_usermeta_form_field_birthday($user)
{
    ?>
    <h3>It's Your Birthday</h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="birthday">Birthday</label>
            </th>
        <td>
            <input type="date"
                class="regular-text ltr"
                id="birthday"
                name="birthday"
                value="<?= esc_attr(get_user_meta($user->ID, 'birthday', true)); ?>"
                title="Please use YYYY-MM-DD as the date format."
                pattern="(190-9][0-9]|20[0-9][0-9]-(1[0-2]|0[1-9])-(3[01]|[21][0-9]|0[1-9])"
                required>
            <p class="description">
                Please enter your birthday date.
            </p>
        </td>
    </tr>
</table>
<?php
}

/**
 * The save action.
 * 
 * @param $user_id int the ID of the current user.
 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure
 */
function pdh_usermeta_form_field_birthday_update($user_id)
{
    // check that the current user has the capability to edit the $user_id
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    // create/update user meta for the $user_id
    return update_user_meta(
        $user_id,
        'birthday',
        $_POST['birthday']
    );
}

// add the field to the user's own profile editing screen
add_action( 'edit_user_profile', 'pdh_usermeta_form_field_birthday' );

// add the field to user profile editing screen
add_action( 'show_user_profile', 'pdh_usermeta_form_field_birtday' );

// add the save action to user's own profile editing screen update
add_action( 'personal_options_update', 'pdh_usermeta_form_field_birthday' );

// add the save action to user profile editing screen update
add_action( 'edit_user_profile_update', 'pdh_usermeta_form_field_birthday_update' );

// We can also work programmatically, e.g., creating a custom user area and/or disabling access to WP admin area
// Available Functions: add_user_meta(), update_user_meta(), delete_user_meta(), get_user_meta()

// Add User Meta
// https://developer.wordpress.org/reference/functions/add_user_meta/
// add_user_meta( int $user_id, string $meta_key, mixed $meta_value, bool $unique = false );

// Update User Meta
// https://developer.wordpress.org/reference/functions/update_user_meta/
// update_user_meta( int $user_id, string $meta_key, mixed $meta_value, mixed $prev_value = '' );

// Delete User Meta
// https://developer.wordpress.org/reference/functions/delete_user_meta/
// delete_user_meta( int $user_id, string $meta_key, mixed $meta_value = '' );

// Get User Meta
// https://developer.wordpress.org/reference/functions/get_user_meta/
// get_user_meta( int $user_id, string $key = '', bool $single = false )
// If you only provide $user_id, all metadata associated with that user will be returned in an associative array (https://en.wikipedia.org/wiki/Associative_array)

// Roles & Capabilities: https://developer.wordpress.org/plugins/users/roles-and-capabilities/
// Roles define a set of capabilities for a user.
// Default Roles in WP: Super Admin, Administrator, Editor, Author, Contributor, Subscriber

// Adding a Role
// https://developer.wordpress.org/reference/functions/add_role/
function pdh_simple_role()
{
    add_role(
        'simple_role',
        'Simple Role',
        [
            'read'          => true,
            'edit_posts'    => true,
            'upload_files'  => true,
        ]
        );
}

// add the simple_role
add_action('init', 'pdh_simple_role');

// Once a role is called it is added to db. Running add_role again will not modifying anything!!!
// To modify capabilities in bulk, remove role then re-add: remove_role()
// https://developer.wordpress.org/reference/functions/remove_role/
function pdh_simple_role_remove()
{
    remove_role('simple_role');
}

// remove the simple role
add_action('init', 'pdh_simple_role_remove');

// One can remove default roles, but additional considerations needed.

// Capabilities define what a role can and can not do: e.g., edit posts, publish posts, etc.
// Add Capabilities to an Existing Role
// https://developer.wordpress.org/reference/functions/get_role/
function pdh_simple_role_caps()
{
    // gets the simple_role role object
    $role = get_role('simple_role');

    // add a new capability
    $role->add_cap('edit_others_posts', true);
}

// add simple_role capabilities, priority must be after initial role definition
add_action('init', 'pdh_simple_role_caps', 11);

// Its possible to add custom caps to built-in roles but these will not be active in default WP admin screens, only custom admin screens and front-end.

// Removing capabilities
function pdh_simple_role_caps_remove()
{
    // gets the simple_role role object
    $role = get_role('simple_role');

    // remove capability
    $role->remove_cap('edit_others_posts'); // do we need true false?
}

add_action('init', 'pdh_simple_role_caps', 12);

// Check if a user has a specific role/capability
// user_can( int|object $user, string $capability, array $args );
// $args is optional, may include an object against which test should be performed, e.g., passing a post ID

// Current User Can wraps user_can and uses current user object
// current_user_can( string $capability );
if (current_user_can('edit_posts')) {
    edit_post_link('Edit', '<p>', '</p>');
}
