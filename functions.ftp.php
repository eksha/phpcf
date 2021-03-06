<?PHP

/*
 * Upload a directory
 */
function ftpPutAll($conn_id, $src_dir, $dst_dir) {
    $d = dir($src_dir);
    while($file = $d->read()) { // do this for each file in the directory
        if ($file != "." && $file != "..") { // to prevent an infinite loop
            if (is_dir($src_dir."/".$file)) { // do the following if it is a directory
                if (!@ftp_chdir($conn_id, $dst_dir."/".$file)) {
                    ftp_mkdir($conn_id, $dst_dir."/".$file); // create directories that do not yet exist
                }
                ftpPutAll($conn_id, $src_dir."/".$file, $dst_dir."/".$file); // recursive part
            } else {
                $upload = ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY); // put the files
            }
        }
    }
    $d->close();
}


function ftp_rrmdir($handle, $directory)
{   # here we attempt to delete the file/directory
    if( !(@ftp_rmdir($handle, $directory) || @ftp_delete($handle, $directory)) )
    {            
        # if the attempt to delete fails, get the file listing
        $filelist = @ftp_nlist($handle, $directory);
        // var_dump($filelist);exit;
        # loop through the file list and recursively delete the FILE in the list
        foreach($filelist as $file) {            
           ftp_rrmdir($handle, $file);            
        }
        ftp_rrmdir($handle, $directory);
    }
}
