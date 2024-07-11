<?php
require 'view/expotrAsFile.php';


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
</body>
</html>
<style>
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        margin: 0;
        background-color: #f4f4f4;
    }
</style>

<?php
//
//$servername = "localhost";
//$username = "root";
//$password = "iskan2066";
//$dbname = "tgBot";
//
//$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
//
//
//$sql = "SELECT id, chatId, amount, conType, date FROM data";
//$result = $conn->query($sql);
//
//
//
//?>

<div class="container">
<table class="table">
    <p><strong>1 USD = 12632.88 UZS</strong></p>

    <?php

    // Database credentials
    $servername = "localhost";
    $username = "root";
    $password = "iskan2066";
    $dbname = "tgBot";

    try {
        // Connect using PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exceptions

        // Prepare and execute the SQL query
        $sql = "SELECT id, chatId, amount, conType, date FROM data"; // Note: use backticks for reserved keywords
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Fetch the results and build the table dynamically
        echo "<table class='table'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th scope='col'>#</th>";
        echo "<th scope='col'>Id</th>";
        echo "<th scope='col'>Chat Id</th>";
        echo "<th scope='col'>Amount</th>";
        echo "<th scope='col'>Conversation type</th>";
        echo "<th scope='col'>Date</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $i = 1;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<th scope='row'>$i</th>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["chatId"] . "</td>";
            echo "<td>" . $row["amount"] . "</td>";
            echo "<td>" . $row["conType"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo "</tr>";
            $i++;
        }

        echo "</tbody>";
        echo "</table>";

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null; // Close the database connection
    ?>
    <form action="expotrAsFile.php" method="post">
    <button type="submit" class="btn btn-success" name="export">Export As File</button>
    </form>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

