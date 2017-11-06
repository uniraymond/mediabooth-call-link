<?php
/**
 * Created by Media Booth.
 * User: Raymond F.
 * Date: 2017-10-17
 */

?>
<?php
if ( is_admin() ) {
    require_once MCL_PLUGIN_DIR . '/admin/admin.php';
} else {
    require_once MCL_PLUGIN_DIR . '/includes/mcl.php';
}