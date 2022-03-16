<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true)) {
    if ((isset($_GET["type"]) && isset($_GET["page"])) == False) {
        header("location: index.php?type=movie&page=0");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Tracker</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>

    <div class="hud">
        <div class="top-nav">
            <a href="index.php?type=movie&page=0">Movie List</a>
            <a href="index.php?type=short&page=0">Shorts List</a>
            <a href="index.php?type=tvSeries&page=0">Series List</a>
        </div>
        <div class="top-nav-right">
            <?php
                if ($_SESSION["username"] == "admin") {
                    echo "<a href='./TSVs/update_db.php'>Update DB</a>";
                }
            ?>
            <a href="watchlist.php">Watchlist</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="flex-column" style="margin-top: auto;">
            <div class="search">
                <form action="search.php" method="GET">
                    <input type="text" placeholder="Search for a movie" name="movie-name">
                    <input type="submit" value="Search">
                </form>
            </div>

            <div id="movie-list" class="flex-center">
                <div class="flex-column">
                    <?php
                    // Connect to the database
                    require_once "config.php";

                    // Request movie descriptions from the database and order them by the release year
                    $sql = "SELECT * FROM `descriptions` WHERE titleType = '" . $_GET["type"] . "' ORDER BY startYear DESC
                        LIMIT " . ($_GET["page"] * 20). ", 20";
                    $result = $mysqli->query($sql) or die($mysqli->error);

                    // Build an html table
                    echo "<table id='movies' class='flex-center'";
                    echo "<tr>";
                    echo "<th></th>";
                    echo "<th>" . "Title" . "</th>";
                    echo "<th>" . "Genres" . "</th>";
                    echo "<th>" . "Rating" . "</th>";
                    echo "</tr>";

                    $titles = [];

                    if ($property = $result->fetch_all(MYSQLI_BOTH)) {
                        foreach ($property as $row) {
                            array_push($titles, "'" . $row["titleId"] . "'");
                        }
                        
                        // Request ratings from the database
                        $sql_rating = "SELECT * FROM `ratings` WHERE titleId IN (" . implode(",", $titles) . ");";
                        $result_rt = $mysqli->query($sql_rating) or die($mysqli->error);
                        $rating = $result_rt->fetch_all(MYSQLI_BOTH);
                        
                        // Show the first 20 movies
                        for ($i = 0; $i < 20; $i++) {
                            echo "<tr>";
                            // Watchlist button
                            echo "<td style='width: 15rem;'><div>
                                <a href='add_to_list.php?id=".$property[$i]["titleId"]."&type=".$property[$i]["titleType"]."' class='btn-watchlist'>
                                Add to watchlist</a>
                                </div></td>";
                            
                            // Title
                            echo "<td><h2>" . $property[$i]["primaryTitle"] . " ";

                            // Year
                            if ($property[$i]["titleType"] == "tvSeries" && !empty($property[$i]["endYear"])) {
                                echo "(" . $property[$i]["startYear"] . " - " . $property[$i]["endYear"] . ")</h2>";
                            } else {
                                echo "(" . $property[$i]["startYear"] . ")</h2>";
                            }

                            // Original Title
                            echo "<p class='orig-title'><i>Original: " . $property[$i]["originalTitle"]. "</i> ";
                            // Duration
                            echo "<p><i>Duration(mins): </i>" . $property[$i]["runtimeMinutes"] . "</p></td>";
                            // Genres
                            echo "<td>" . $property[$i]["genres"] . "</td>";
                            // Rating
                            echo "<td><p>" . (isset($rating[$i]["averageRating"]) ? $rating[$i]["averageRating"] : "-") . "</p>";
                            echo "<p style='font-size: 1.3rem;'>(". (isset($rating[$i]["numVotes"]) ? $rating[$i]["numVotes"] : "0") ." votes)</p>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }

                    echo "</table>";
                    echo "</div>";

                    // Bottom navigation
                    echo "<div>";
                    echo "<table class='flex-center flex-column'>";
                    echo "<tr>";
                    
                    // Home page button
                    echo "<td><a href='./index.php?type=".$_GET["type"]."&page=0' class='btn btn-nav'>Home</a></td>";
                    // Back page button
                    $page = $_GET["page"];
                    if ($page < 1) {
                        $page = 0;
                    } else {
                        echo "<td><a href='./index.php?type=".$_GET["type"]."&page=" . ($page - 1) . "' class='btn btn-nav'>Back</a></td>";
                    }
                    // Next page button
                    echo "<td><a href='./index.php?type=".$_GET["type"]."&page=" . ($page + 1) . "' class='btn btn-nav'>Next</a></td>";
                    // Select the page form
                    echo "<td><div><form class='form-nav' action='index.php?type=".$_GET["type"]." method='get'>
                    <input type='number' name='page' value='" . $page . "'>
                    <input type='hidden' name='type' value=".$_GET["type"].">
                    <input type='submit' value='Go'></form></div></td>";

                    echo "</tr>";
                    echo "</table>";
                    echo "</div>";

                    $mysqli->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
} else {
    header("location: login.php");
    exit();
}
?>