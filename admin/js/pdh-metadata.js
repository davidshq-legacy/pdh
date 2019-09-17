// By default, $_POST super global is parsed after the user has submitted the page.
// Using AJAX one can update as user input occurs.
// See: https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
// Step 1: Define a Trigger
// A link click, change of value, or any other JS event.
// Look into jslint and plusplus
/*jslint browser: true, plusplus: true */
(function ($, window, document) {
    'use strict';
    // execute when the DOM is ready
    $(document).ready(function () {
        // js 'change' event triggered on the pdh_field form field
    $('#pdh_field').on('change', function () {
        // what the trigger actually does
        // jQuery post method, a shorthand for $.ajax with POST
        $.post(pdh_meta_box_obj.url, // or if you only need WP Ajax file URL; you can use predefined JS variable ajaxurl, this is only available in WP admin, check if it is empty before acting
            {
                action: 'pdh_ajax_change', // POST data, action
                pdh_field_value: $('#pdh_field').val() // POST data, pdh_field_value
            }, function (data) {
                // handle response data
                if (data === 'success') {
                    // do success codee
                } else if (data === 'failure') {
                    // do fail code
                } else {
                    // do nothing
                }
            }
        );
    });
});
}(jQuery, window, document));
