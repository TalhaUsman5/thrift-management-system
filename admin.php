<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "thrift_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the variables for checking the payment window
$currentDate = date("Y-m-d");
$lastDayOfMonth = date("Y-m-t");

if ($_SERVER["REQUEST_METHOD"] == "POST" && $currentDate == $lastDayOfMonth) {
    // Logic to determine the next member in rotation
    $next_member_query = $conn->query("SELECT id FROM members ORDER BY rotation_order LIMIT 1");
    $next_member = $next_member_query->fetch_assoc()['id'];

    // Fetch and reset contributions
    $total_funds = 0;
    $result = $conn->query("SELECT * FROM members WHERE contribution_status = 'Contributed'");
    while ($row = $result->fetch_assoc()) {
        $total_funds += 100; // Example contribution amount
        $conn->query("UPDATE members SET contribution_status = 'Pending' WHERE id = " . $row['id']);
    }

    // Update the next member's rotation order to the end and adjust other members' orders
    $conn->query("UPDATE members SET rotation_order = rotation_order + 1 WHERE id != $next_member");
    $conn->query("UPDATE members SET rotation_order = 1 WHERE id = $next_member");

    echo "Funds forwarded to member ID: $next_member. Total collected: $" . $total_funds;
} else {
    echo "Funds can only be forwarded at the end of the payment window.";
}
include 'admin.html';
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <main>
        <section id="distribution">
            <h2>Distribute Funds</h2>
            <form method="POST">
                <button type="submit">Distribute Funds</button>
            </form>
        </section>
        
    </main>
</body>
</html>
