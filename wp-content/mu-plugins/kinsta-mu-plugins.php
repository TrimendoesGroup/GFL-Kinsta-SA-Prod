<?php
define( 'KINSTAMU_VERSION', '1.5.3' );
if( !defined('KINSTAMU_WHITELABEL') ) {
    define('KINSTAMU_WHITELABEL', false);
}

require( 'kinsta-mu-plugins/admin-text-modifications/admin-text-modifications.php' );
require( 'kinsta-mu-plugins/ip-ban/ip-ban.php' );
require( 'kinsta-mu-plugins/shared/KinstaTools.php' );
require( 'kinsta-mu-plugins/kinsta-cache/kinsta-cache.php' );
