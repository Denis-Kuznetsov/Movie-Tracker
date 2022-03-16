<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true)) {
    if (isset($_GET["page"]) == False) {
        header("location: watchlist.php?page=0");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist</title>

    <?php
        echo "<script>
        // Check if the user has watchlist in local storage
        watchlist = JSON.parse(localStorage.getItem('watchlist'));

        if (watchlist == null || (watchlist['username'] != '".$_SESSION["username"]."')) {";
                // Connect to the database
                require "config.php";
                // Get the watchlist from the database
                $sql = "SELECT * FROM watchLists WHERE username='".$_SESSION["username"]."'";
                if ($result = $mysqli->query($sql)) {
                    if ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                        $json = json_encode($data);

                        // Save the watchlist to the local storage
                        echo "console.log('Watchlist Saved'); localStorage.setItem('watchlist', `". $json ."`);";
                    } else {
                        echo "console.log('Watchlist Saved'); localStorage.setItem('watchlist', `{}`);";
                    }
                } 
        echo "}
        </script>\n";
    ?>
    <?php
        echo "
        <script>
            // Http request 
            // Sending wathclist from the local storage to the php script
            function loadXMLDoc(data) {
                var xmlhttp = new XMLHttpRequest();
                
                // Open 'get_info.php' script
                xmlhttp.open('POST', 'get_info.php', true);

                xmlhttp.onreadystatechange = function() {
                    // If the server recieves the watchlist the loader hides and watchlist appears
                    if (xmlhttp.readyState == XMLHttpRequest.DONE) {   // XMLHttpRequest.DONE == 4
                        if (xmlhttp.status == 200) {
                            const text = document.getElementById('movie-list');
                            const loader = document.getElementById('loader');
                            const watchlist = document.getElementById('watchlist');
                            const bottomNav = document.getElementById('bottom-nav');

                            // Show the watchlist data
                            text.innerHTML = xmlhttp.responseText;

                            // Hide loader and show the table
                            loader.classList.add('hidden');
                            watchlist.classList.remove('hidden');
                            bottomNav.classList.remove('hidden');
                        }
                        else if (xmlhttp.status == 400) {
                            alert('There was an error 400');
                        }
                        else {
                            alert('Something went wrong: ' + xmlhttp.error);
                        }
                    }
                }
                
                // Request to show watchlist data
                xmlhttp.setRequestHeader('Content-type', 'application/json');
                xmlhttp.send(data);
            }

            // Send watchlsit from local storage
            watchlist = JSON.parse(localStorage.getItem('watchlist'));
            watchlist['page'] = ".$_GET['page'].";
            loadXMLDoc(JSON.stringify(watchlist));
        </script>\n";
    ?>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="hud">
        <a href="index.php?type=movie&page=0">Home Page</a>
    </div>
    <div class="container">
        <div class="flex-column flex-center" style="margin-top: auto; margin-bottom: auto;">
            <div class="text-center">
                <h1 style="margin:1rem; display: inline-flex; vertical-align: middle;">Watchlist</h1>
                <a href="update.php" class="btn-watch" style="vertical-align: middle;">Update</a>
            </div>
            <div id="loader"></div>

            <div class="flex-column hidden" id="watchlist">
                <div id="movie-list"></div>
            </div>

            <?php
            // Bottom navigation
                echo "<div id='bottom-nav' class='hidden'>";
                echo "<table class='flex-center flex-column'>";
                echo "<tr>";
                
                // Home page button
                echo "<td><a href='./watchlist.php?page=0' class='btn btn-nav'>Home</a></td>";
                
                // Back page button
                $page = $_GET["page"];
                if ($page < 1) {
                    $page = 0;
                } else {
                    echo "<td><a href='./watchlist.php?page=" . ($page - 1) . "' class='btn btn-nav'>Back</a></td>";
                }
                
                // Next page button
                echo "<td><a href='./watchlist.php?page=" . ($page + 1) . "' class='btn btn-nav'>Next</a></td>";
                
                // Select the page form
                echo "<td><div><form class='form-nav' action='watchlist.php' method='get'>
                <input type='number' name='page' value='" . $page . "'>
                <input type='submit' value='Go'></form></div></td>";

                echo "</tr>";
                echo "</table>";
                echo "</div>";
            ?>
        </div>
    </div>
</body>
</html>

<?php
} else {
    header("location: login.php");
}
?>