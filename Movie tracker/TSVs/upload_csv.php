<?php 
    // Connect to the database
    require_once "../config.php";

    // Clean the database
    $sql_clean = "DELETE FROM descriptions";
    $result_clean = $mysqli->query($sql_clean) or die($mysqli->error);
    $sql_clean = "ALTER TABLE descriptions AUTO_INCREMENT=1";
    $result_clean = $mysqli->query($sql_clean) or die($mysqli->error);
    
    // Load tsv file to the database
    $sql = 
        'LOAD DATA LOCAL INFILE 
        "title.basics.tsv" IGNORE 
        INTO TABLE descriptions
        FIELDS TERMINATED BY \'\t\' 
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES
        (titleId, titleType, primaryTitle, originalTitle, isAdult, startYear, endYear, runtimeMinutes, genres)';
    
    $result = $mysqli->query($sql) or die($mysqli->error);

    // Clean the database
    $sql_clean = "DELETE FROM episodes";
    $result_clean = $mysqli->query($sql_clean) or die($mysqli->error);
    $sql_clean = "ALTER TABLE episodes AUTO_INCREMENT=1";
    $result_clean = $mysqli->query($sql_clean) or die($mysqli->error);
    
    // Load tsv file to the database
    $sql = 
        'LOAD DATA LOCAL INFILE 
        "title.episode.tsv" IGNORE 
        INTO TABLE episodes
        FIELDS TERMINATED BY \'\t\' 
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES
        (titleId, parentId, seasonNumber, episodeNumber)';
    
    $result = $mysqli->query($sql) or die($mysqli->error);

    // Clean the database
    $sql_clean = "DELETE FROM ratings";
    $result_clean = $mysqli->query($sql_clean) or die($mysqli->error);
    $sql_clean = "ALTER TABLE ratings AUTO_INCREMENT=1";
    $result_clean = $mysqli->query($sql_clean) or die($mysqli->error);

    // Load tsv file to the database
    $sql = 
        'LOAD DATA LOCAL INFILE 
        "title.ratings.tsv" IGNORE 
        INTO TABLE ratings
        FIELDS TERMINATED BY \'\t\' 
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES
        (titleId, averageRating, numVotes)';
    
    $result = $mysqli->query($sql) or die($mysqli->error);
    
    echo "TSVs loaded to the DB";

    $mysqli->close();
    exit();
?>