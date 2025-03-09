<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "thrift_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$member_id = $_SESSION['member_id'];
$result = $conn->query("SELECT * FROM members WHERE id = $member_id");
$member = $result->fetch_assoc();

$currentDate = date("Y-m-d");
$lastDayOfMonth = date("Y-m-t");
$firstDayOfPaymentWindow = date("Y-m-", strtotime($lastDayOfMonth)) . (date("t") - 6);

if ($currentDate >= $firstDayOfPaymentWindow && $currentDate <= $lastDayOfMonth) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $contribution_status = "Contributed";
        $conn->query("UPDATE members SET contribution_status = '$contribution_status' WHERE id = $member_id");
        echo "Contribution made successfully!";
    }
} else {
    echo "You can only make contributions during the payment window.";
}

$conn->close();
?>
