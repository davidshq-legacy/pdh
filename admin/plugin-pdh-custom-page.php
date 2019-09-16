<?php
/**
 * Creating a Full Custom Settings Page: https://developer.wordpress.org/plugins/settings/custom-settings-page/
 * This example adds a Top-Level Menu named PDH, registers a custom option named pdh_options, and performs CRUD logic using Setings and Options APIs.
 * @internal never define functions inside callbacks, these functions could be run multiple times; this would result in a fatal error.
 */
 /**
  * custom option and settings
  */
  function pdh_settings_init() {
    // register a new setting for "pdh" page
    register_setting( 'pdh', 'pdh_options' );

    // register a new section in the "pdh" page
    add_settings_section( 
        'pdh_section_developers', 
        __( 'The Matrix has you.', 'pdh' ),
         'pdh_section_developers_cb',  
         'pdh'
    );

    // register a new field in the 'pdh_section_developers' section, include the 'pdh' page
    add_settings_field(
        'pdh_field_pill', // Used only internally as of WP 4.6
        // use $args label_for to populate the id inside the callback
        __( 'Pill', 'pdh' ),
        'pdh_field_pill_cb',
        'pdh',
        'pdh_section_developers',
        [
            'label_for' => 'pdh_field_pill',
            'class' => 'pdh_row',
            'pdh_custom_data' => 'custom',
        ]
        );
      }

  /**
   * register our pdh_settings_init to the admin_init action hook
   */
  add_action( 'admin_init', 'pdh_settings_init' );

  /**
   * custom option and settings:
   * callback functions
   */

   // developers section cb

   // section callbacks can accept an $args parameter, which is an array.
   // $args have the following keys defined: title, id, callback.
   // the values are defined at the add_settings_section() function.
   function pdh_section_developers_cb( $args ) {
       ?>
       <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'pdh' ); ?></p>
       <?php
   }

     // pill field cb

     // field callbacks can accept an $args parameter, which is an array.
     // $args is defined at the add_settings_field() function.
     // wordpress has magic interaction with the following keys: label_for, class.
     // the label_for key value is used for the "for" attribute of the <label>.
     // the class key value is used for the "class" attribute of the <tr> containing the field.
     // you can add custom key value pairs to be used inside your callbacks.
     function pdh_field_pill_cb( $args ) {
        // get the value of the setting we've registered with register_setting()
        $options = get_option( 'pdh_options' );
        // output the field
        ?>
        <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
        data-custom="<?php echo esc_attr( $args['pdh_custom_data'] ); ?>"
        name="pdh_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
        >
        <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
        <?php esc_html_e( 'red pill', 'pdh' ); ?>
        </option>
        <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
        <?php esc_html_e( 'blue pill', 'pdh' ); ?>
        </option>
        </select>
        <p class="description">
        <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'pdh' ); ?>
        </p>
        <p class="description">
        <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'pdh' ); ?>
        </p>
        <?php
     }

     /**
      * top level menu
      */
      function pdh_options_page() {
          // add top level menu page
          add_menu_page(
              'PDH',
              'PDH Options',
              'manage_options',
              'pdh',
              'pdh_options_page_html'
          );
      }

      /**
       * register our pdh_options_page to the admin_menu action hook
       */
      add_action( 'admin_menu', 'pdh_options_page' );

      /**
       * top level menu:
       * callback functions
       */
      function pdh_options_page_html() {
          // check user capabilities
          if ( ! current_user_can( 'manage_options' ) ) {
              return;
          }

          // add error/update messages

          // check if the user has submitted the settings
          // wp will add the 'settings-updated' $_GET parameter to the url
          if ( isset( $_GET['settings-updated'] ) ) {
              // add settings saved message with the class of "updated"
              add_settings_error( 'pdh_messages', 'pdh_message', __( 'Settings Saved', 'pdh' ), 'updated' );
          }

          // show error/update messages
          settings_errors( 'pdh_messages');
          ?>
          <div class="wrap">
          <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
          <form action="options.php" method="post">
          <?php
          // output security fields for the registered setting "pdh"
          settings_fields( 'pdh' );
          // output setting sections and their fields
          // (sections are registered for pdh, each field is registered to a specific section)
          do_settings_sections( 'pdh' );
          // output save settings button
          submit_button( 'Save Settings' );
          ?>
          </form>
          </div>
          <?php
        }