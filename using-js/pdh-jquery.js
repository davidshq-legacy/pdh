// A basic jQuery statement has two parts:
// 1: A selector that determines which HTMl element the code applies to
// 2: An action or event determining what the code does or reacts to.
// jQuery.(selector).event(function);
// Note: Code lacks sanitization, security, error handling, and internationalization
// There are many forms of jQuery Selectors: http://api.jquery.com/category/selectors
// two of the most common are ".class" and "#id".
// There are also many different events (below is change, another common one is click): http://api.jquery.com/category/events/
// AJAX: https://developer.wordpress.org/plugins/javascript/ajax/
// This focuses on jQuery only as PDH states, "Because handling HTTP requests with JavaScript is awkward and jQuery is bundled into WordPress anyway..."
// Probably look into adding straight JS at some point here.
jQuery(document).ready(function($) { // wrapper
    $(".pref").change(function(){ // event
        var this2 = this; // use in callback
        $.post(my_ajax_obj.ajax_url, { // POST request
            // WP is not using true nonce, it will always yield same number with the same seed phrase over 12 hour period.
            _ajax_nonce: my_ajax_obj.nonce, // nonce
            action: "my_tag_count", // action
            title: this.value // data
        }, function(data) {
            this2.nextSibling.remove();
            $(this2).after(data); // data contains entire server response
        });
    });
});








