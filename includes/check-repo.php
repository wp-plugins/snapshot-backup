<?php
// CHECK REPOSITORY
// since @2.0

// Direct calls to this file are Forbidden when core files are not present
// Thanks to Ed from ait-pro.com for this  code 
// @since 2.1

if ( !function_exists('add_action') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}

if ( !current_user_can('manage_options') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}

// 
//

?>
<p><strong>Here's a list of Snapshots in your repository:</strong></p>
<?php

// set up variables
$host = get_option('snapshot_ftp_host');
$user = get_option('snapshot_ftp_user');
$pass = get_option('snapshot_ftp_pass');
$subdir = get_option('snapshot_ftp_subdir');

// extra security
// @since 2.1
// If in WP Dashboard or Admin Panels
if ( is_admin() ) {
// If user has WP manage options permissions
if ( current_user_can('manage_options')) {
// connect to host ONLY if the 2 security conditions are valid / met
$conn_id = ftp_connect($host);
}
}

// login with username and password
$login_result = ftp_login($conn_id, $user, $pass);

// get contents of the current directory
$contents = ftp_nlist($conn_id, "$subdir/*.tar");

// output $contents
// var_dump($contents);

?><ol></em>

<?php foreach ($contents as $key => $value) {
echo '<li>' . substr($value, (strlen($subdir))) . '</li>';
}
?>
</ol>
<br />
<em>This section helps you keep track of your backups. </em><br />
<em>I'll add more functions here in future versions of the Plugin.</em>

<?php
echo "<br />";


ftp_close($conn_id);
?>
