<?php
session_start();
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}
include 'contribute.php'; // Include contribute.php to import the variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thrift_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$member_id = $_SESSION['member_id'];
$result = $conn->query("SELECT * FROM members WHERE id = $member_id");
$member = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contribution_status = "Contributed";
    $conn->query("UPDATE members SET contribution_status = '$contribution_status' WHERE id = $member_id");
    echo "Contribution made successfully!";
}
include 'member.html';
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Member Dashboard</h1>
    </header>
    <main>
        <section id="contribution">
            <h2>Make Contribution</h2>
            <form method="POST" action="contribute.php">
                <p>Status: <?php echo $member['contribution_status']; ?></p>
            </form>
        </section>
    </main>
</body>
</html>

