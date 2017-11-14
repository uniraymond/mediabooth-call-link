<?php
if ( is_admin() ) {
    require_once( dirname(__FILE__) . '/admin/admin.php');
} else {
    require_once( dirname(__FILE__) . '/mcl.php');
}