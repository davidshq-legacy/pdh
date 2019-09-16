<?php

if ( is_admin() ) {
    // in admin mode
    require_once( dirname( __FILE__ ) . '/admin/plugin-name-admin.php' );
}