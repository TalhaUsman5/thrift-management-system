<?php
session_start();
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email)) {
        echo "Name and email are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
    } else {
        $update_query = "UPDATE members SET name='$name', email='$email'";
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_query .= ", password='$hashed_password'";
        }
        $update_query .= " WHERE id=$member_id";
        if ($conn->query($update_query) === TRUE) {
            echo "Profile updated successfully!";
        } else {
            echo "Error: " . $update_query . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Member Profile</h1>
    </header>
    <main>
        <section id="profile">
            <h2>Update Profile</h2>
            <form method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $member['name']; ?>" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $member['email']; ?>" required>
                <label for="password">Password (leave blank to keep current):</label>
                <input type="password" id="password" name="password">
                <button type="submit">Update Profile</button>
            </form>
        </section>
    </main>
</body>
</html>
