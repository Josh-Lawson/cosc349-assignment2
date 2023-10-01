<?php
include '../common/db_config.php';

/**
 * @file
 * This file is used to view users.
 * 
 */


/**
 * Creates a new mysqli object and connects to the database
 */
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Checks if the request method is POST and sets the filter variable
 */
$filter = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filter = $_POST["filter"];
}

/**
 * Prepares a SQL statement to get all users
 */
$sql = "SELECT * FROM User WHERE role = 'user'";
if ($filter != "") {
    $sql = $sql . " AND (name LIKE '%$filter%' OR username LIKE '%$filter%')";
}

$result = $conn->query($sql);

?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>View Users</title>
    <link rel="stylesheet" type="text/css" href="../common/style/style.css">
</head>

<body>
    <header>
        <?php include '../common/navbar.php'; ?>
    </header>
    <main>
        <h1>View Users</h1>

        <form action="view_users.php" method="POST">
            <div>
                Search: <input type="text" name="filter" value="<?php echo $filter; ?>" />
                <input type="submit" value="Filter" />
            </div>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Date Created</th>
                <th>Delete User</th>
            </tr>
            <?php
            /**
             * Loops through the results from the database and displays them in a table
             */
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["userId"] . "</td>";
                    echo "<td>" . $row["username"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["createdDate"] . "</td>";
                    echo "<td><a href='delete_user.php?userId=".$row["userId"]."'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>
        </table>
    </main>
</body>

</html>