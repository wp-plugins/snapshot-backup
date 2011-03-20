<?php
echo "<h2>Archving your website</h2>";
echo "This could take another moment...<br />";
$savepath = 'wp-content/uploads/';

// delete previous backups
$output = exec('rm wp-content/uploads/*snapshot-*.tar');
// generate filename for backup
$prefix = get_option('snapshot_ftp_prefix');
if (!$prefix == ''){
$filename = $prefix.'-snapshot-'.$filetime.'.tar';
} else {
$filename = 'snapshot-'.$filetime.'.tar';
}
// echo "<br>The filename will be $filename <br>";
// write filename to the database 
update_option('snapshot_latest', $filename);
// echo "<br>saving backup in $savepath";

// execute tar at system level
$output = exec("tar -cvf $savepath$filename *");  
// get size of latest file
echo "Size of this snapshot is " . round((filesize($savepath.$filename)/1024/1024), 2)." MB<br>";
// delete database file
$output = exec("rm wp-content/uploads/snapshot-db-*.sql");

// echo "<br>Done!";
?>
