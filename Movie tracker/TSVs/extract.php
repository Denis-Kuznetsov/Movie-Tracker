<?php
    ini_set('memory_limit', '256M');

    //This input should be from somewhere else, hard-coded in this example
    
    $file_name = 'title.episode.tsv.gz';
    $out_file_name = str_replace('.gz', '', $file_name); 

    // Open our files (in binary mode)
    $file = gzopen($file_name, 'rb');
    $out_file = fopen($out_file_name, 'wb'); 

    stream_copy_to_stream($file, $out_file);

    // Files are done, close files
    fclose($out_file);
    gzclose($file);

    
    //This input should be from somewhere else, hard-coded in this example
    $file_name = 'title.basics.tsv.gz';
    $out_file_name = str_replace('.gz', '', $file_name); 

    // Open our files (in binary mode)
    $file = gzopen($file_name, 'rb');
    $out_file = fopen($out_file_name, 'wb'); 

    stream_copy_to_stream($file, $out_file);

    // Files are done, close files
    fclose($out_file);
    gzclose($file);

    //This input should be from somewhere else, hard-coded in this example
    $file_name = 'title.ratings.tsv.gz';
    $out_file_name = str_replace('.gz', '', $file_name); 

    // Open our files (in binary mode)
    $file = gzopen($file_name, 'rb');
    $out_file = fopen($out_file_name, 'wb'); 

    stream_copy_to_stream($file, $out_file);

    // Files are done, close files
    fclose($out_file);
    gzclose($file);

    echo "Exctracted successfully!";

    unset($file);
    unset($out_file);
?>