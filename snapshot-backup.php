<?php
/*
Plugin Name: Snapshot Backup
Plugin URI: http://wpguru.co.uk/2011/02/snapshot-backup/
Description: Backs up your ENTIRE Wordpress site and sends it to an FTP archive. Excellent!
Author: Jay Versluis
Version: 1.2
Author URI: http://wpguru.co.uk
License: GPLv2 or later

Copyright 2011 by Jay Versluis (email : versluis2000@yahoo.com)

This is Version 1.2.0 as of 23/02/2011

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

// Hook for adding admin menu
add_action('admin_menu', 'snapshot_admin');

// action function for above hook
function snapshot_admin() {

// Add a new submenu under DASHBOARD
add_dashboard_page('Snapshot Backup', 'Snapshot Backup', 'administrator', 'snapshot-admin', 'snapshot');
}

// displays the page content for the admin submenu
function snapshot() {

//must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $opt_name = 'snapshot_ftp_host';
    $opt_name2 = 'snapshot_ftp_user';
    $opt_name3 = 'snapshot_ftp_pass';
    $opt_name4 = 'snapshot_ftp_subdir';
	$opt_name5 = 'snapshot_ftp_prefix';
    $hidden_field_name = 'snapshot_ftp_hidden';
    $hidden_field_name2 = 'snapshot_backup_hidden';
    $hidden_field_name3 = 'snapshot_check_repo';
    $data_field_name = 'snapshot_ftp_host';
    $data_field_name2 = 'snapshot_ftp_user';
    $data_field_name3 = 'snapshot_ftp_pass';
    $data_field_name4 = 'snapshot_ftp_subdir';
	$data_field_name5 = 'snapshot_ftp_prefix';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );
    $opt_val2 = get_option ($opt_name2 );
    $opt_val3 = get_option ($opt_name3 );
    $opt_val4 = get_option ($opt_name4 );
	$opt_val5 = get_option ($opt_name5 );


    // reset working directory to WP root
    chdir('../');

/****************************************************
/ SNAPSHOT ADMIN AREA
/****************************************************/
// Header
?>
<div class="wrap">
<h2><img src="/wp-content/plugins/snapshot-backup/WP-Guru-Logo.png">&nbsp;Welcome to Snapshot Backup!</h2>
<table class="snapshot-backup" width=600 cellspacing=10 bgcolor=red>
<tr><td>
<p><strong>With this plugin you can create an up-to-the-minute archive of your entire website and save it to an offsite location via FTP.</strong></p>
<p>Things couldn't be easier: </p>
<ul><li>&nbsp;&nbsp;&bull; enter your FTP details at the bottom</li>
<li>&nbsp;&nbsp;&bull; click on CREATE NEW SNAPSHOT</li>
<li>&nbsp;&nbsp;&bull; rest assured you've backed your database AND contents with just one single click</li></ul>
<p>If you don't have an FTP account you can <a href="http://wpguru.co.uk/hosting/ftp/" target="_blank">sign up for one here</a> or download your snapshot from this server once it's done.</p>
</td></tr>
</table>
<p>Any questions? Check out the included <a href="../wp-content/plugins/snapshot-backup/readme.txt" target="_blank">readme file</a> or visit the <a href="http://wpguru.co.uk/2011/02/snapshot-backup/" target="_blank">Snapshot Backup Website</a>. Have fun!</p>

<?php
 if( isset($_POST[ $hidden_field_name2 ]) && $_POST[ $hidden_field_name2 ] == 'Y' ) {

// this could take a while...
// set the number of seconds you'd like to wait for the script here
// default is 300
set_time_limit(300);

// create global file name
$filetime = date('Ymd-Gi');
// echo "<br>The Filetime is $filetime <br>";
// readout the Database
include 'wp-content/plugins/snapshot-backup/database.php';
// create ZIP package
include 'wp-content/plugins/snapshot-backup/zipshot.php';
// send package to FTP
include 'wp-content/plugins/snapshot-backup/sendaway.php';
// we're done
echo '<div class="updated"><h2>All done - thank you!</h2>';
?>
<p><strong>You can download your snapshot file here: <a href="../wp-content/uploads/<?php echo get_option('snapshot_latest'); ?>"><?php echo get_option('snapshot_latest'); ?></a></strong></p></div>
<?php
}

?>

<form name="form2" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name2; ?>" value="Y">
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Create New Snapshot') ?>" />
</p>
</form>
<hr />

<?php
// check FTP Repository for existing backups
// include 'wp-content/plugins/snapshot-backup/list-repo.php'; 
// call FTP Details form
include 'wp-content/plugins/snapshot-backup/ftp-form.php';

// footer
?>
<hr />
<p><strong>Coming soon: automated backups, repository browser, snapshot restore option. Watch this space! </strong></p>
<p>This plugin was brought to you by<br />
  <a href="http://wpguru.co.uk" target="_blank"><img src="/wp-content/plugins/snapshot-backup/guru-header.jpg"></a>
</p>
<p><a href="http://wpguru.co.uk/2011/02/snapshot-backup/" target="_blank">Plugin Home Page</a> | <a href="http://plugins.trac.wordpress.org/log/snapshot-backup/" target="_Blank">Changelog</a> | <a href="http://davidwalsh.name/backup-mysql-database-php" target="_blank">Database Script by the amazing David Walsh</a> | <a href="http://wpguru.co.uk/hosting/ftp/" target="_blank">Get an FTP Account</a> | <a href="http://wpguru.co.uk/say-thanks/" target="_blank">Buy me a Coffee</a></p>

<?php
}
