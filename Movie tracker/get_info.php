<?php
    require "config.php";

    header("Content-Type: application/json");

    // Receive data from http request
    $data = json_decode(file_get_contents("php://input")); 

    // Check if the data is not empty
    if (!empty($data->username) && !empty($data->titles)) {
        $username = $data->username; 
        $types = explode(", ", $data->types);

        // Get movies' descriptions
        $sql_titles = "SELECT * FROM descriptions WHERE titleId IN (". $data->titles .") LIMIT " . ($data->page * 10). ", 10";
        $result_titles = $mysqli->query($sql_titles) or die($mysqli->error);

        // Build an html table 
        echo '<table id="watchlist" class="flex-center">
                <tr>
                    <th></th>
                    <th class="title">Titles</th>
                    <th>Year</th>
                </tr>';

        if ($data_titles = $result_titles->fetch_all(MYSQLI_ASSOC)) {
            foreach ($data_titles as $title) {
                echo "<tr>";
                // Delete button
                echo "<td class='options'>";
                echo "<div class='btn-option'>";
                echo "<a href='delete.php?id=".$title["titleId"]."'>Delete</a>";
                echo "</div>";
                echo "</td>";

                // Title
                echo "<td class='title'>";
                echo "<div style='position: relative;'>";
                echo "<h2 style='display: inline-flex; margin-left: 12.5%;'>".$title["primaryTitle"]."</h2>";
                // Watch button
                if (strpos($data->watchedMovies, $title["titleId"])) {
                    echo "<div class='watched'><a href='delete_watched.php?id=".$title["titleId"]."&type=".$title["titleType"]."' class='btn-watch disabled'>Watched</a></div>";
                } else {
                    echo "<div class='watched'><a href='add_watched.php?id=".$title["titleId"]."&type=".$title["titleType"]."' class='btn-watch enabled'>Watch</a></div>";
                }
                echo "</div>";

                // Original title
                echo "<p><i>Original: </i>".$title["originalTitle"]."</p>";
                
                // Show episodes for TV series
                if ($title["titleType"] == "tvSeries") {
                    // Get all episodes of the tv series
                    $sql_episodes = "SELECT * FROM episodes WHERE parentId = '".$title["titleId"]."' 
                    ORDER BY seasonNumber ASC, episodeNumber ASC";

                    $return = $mysqli->query($sql_episodes) or die($mysqli->error);
                    $episode_titles = [];

                    if ($data_episodes = $return->fetch_all(MYSQLI_ASSOC)) {
                        foreach ($data_episodes as $episode) {
                            array_push($episode_titles, $episode["titleId"]);
                        }
                        
                        // Build an html table
                        echo "<table style='width: 100%;'>";
                        $sql = "SELECT titleId, titleType, primaryTitle, startYear FROM `descriptions` WHERE titleId IN ('".implode("', '", $episode_titles)."')";
                        $return_episodes = $mysqli->query($sql) or die($mysqli->error);
                        $pos = 0;

                        // Header
                        echo '<tr>
                              <th><i>Episode Title</i></th>
                              <th><i>Year</i></th>
                              <th><i>Number</i></th>
                              <th></th>
                              </tr>';

                        while ($episode = $return_episodes->fetch_assoc()) {
                            echo "<tr>";
                            // Title
                            echo "<td>";
                            echo "<p>".$episode["primaryTitle"]."</p>";
                            echo "</td>";
                            // Year
                            echo "<td>";
                            echo $episode["startYear"];
                            echo "</td>";
                            // Season and episode numbers
                            echo "<td>";
                            echo "<p>Season: ".$data_episodes[$pos]["seasonNumber"]."</p>";
                            echo "<p>Episode: ".$data_episodes[$pos]["episodeNumber"]."</p>";
                            echo "</td>";
                            // Watch button
                            echo "<td style='width: 10%;'>";
                            if (strpos($data->watchedEpisodes, $episode["titleId"])) {
                                echo "<div class='watched'><a href='delete_watched.php?id=".$episode["titleId"]."&type=".$episode["titleType"]."' class='btn-watch disabled'>Watched</a></div>";
                            } else {
                                echo "<div class='watched'><a href='add_watched.php?id=".$episode["titleId"]."&type=".$episode["titleType"]."' class='btn-watch enabled'>Watch</a></div>";
                            }
                            echo "</td>";
                            echo "</tr>";
                            $pos += 1;
                        }
                        echo "</table>";
                    }
                    
                }
                    echo "</td><td>";
                    echo $title["startYear"];
                    echo "</td>";
                echo "</tr>";
            }  
        } 
        echo "</table>"; 
    } else echo "";

    $mysqli->close();
    exit();
?>