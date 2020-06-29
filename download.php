<?php
/* Download an image*/

// Check if file is not empty
if (!empty($_GET['file'])) {

    // use pathinfo to be sure the filename itself is not dangerous
    $path_parts = pathinfo($_GET['file']);
    $fileName = $path_parts['basename'];
    $filePath = 'system/img/albums/' . $fileName;

    // get the image and file size
    $mime = ($mime = getimagesize($filePath)) ? $mime['mime'] : $mime;
    $size = filesize( $filePath );

    // check file extension
    $allowed = array('png', 'jpg', 'jpeg');
    $ext = strtolower(pathinfo( $fileName, PATHINFO_EXTENSION));

    // if the file exsists and passed all else start the download using the headers
    if (file_exists($filePath) && !(empty($fileName) && $mime && $size && $ext)) {

        // Define headers
        header("Content-type: " . $mime);
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: ' . $size);

        flush(); // Flush system output buffer

        // Read the file
        readfile($filePath);

        exit;
    } else {
        echo 'The file does not exist.';
//        http_response_code(404);
    }
}
