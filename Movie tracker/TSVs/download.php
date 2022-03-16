<?php
  
    // Initialize a file URL to the variable
    $url_episodes = 
    'https://datasets.imdbws.com/title.episode.tsv.gz';
    $url_descriptions = 
    'https://datasets.imdbws.com/title.basics.tsv.gz';
    $url_ratings = 
    'https://datasets.imdbws.com/title.ratings.tsv.gz';

    // Use basename() function to return the base name of file
    $file_name_episodes = basename($url_episodes);
    $file_name_descriptions = basename($url_descriptions);
    $file_name_ratings = basename($url_ratings);
      
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using base name
    if (file_put_contents($file_name_episodes, file_get_contents($url_episodes)))
    {
        echo "File with episodes downloaded successfully\n";
    }
    else
    {
        echo "File with episodes downloading failed.";
        die("Could not download episodes");
    }

    if (file_put_contents($file_name_descriptions, file_get_contents($url_descriptions)))
    {
        echo "File with descriptions downloaded successfully\n";
    }
    else
    {
        echo "File with descriptions downloading failed.";
        die("Could not download descriptions");
    }

    if (file_put_contents($file_name_ratings, file_get_contents($url_ratings)))
    {
        echo "File with ratings downloaded successfully\n";
    }
    else
    {
        echo "File with ratings downloading failed.";
        die("Could not download ratings");
    }
?>