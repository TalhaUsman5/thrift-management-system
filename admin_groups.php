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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = $_POST['group_name'];
    $conn->query("INSERT INTO groups (name) VALUES ('$group_name')");
    $group_id = $conn->insert_id;
    foreach ($_POST['member_ids'] as $member_id) {
        $conn->query("INSERT INTO group_members (group_id, member_id) VALUES ($group_id, $member_id)");
    }
}

$members_result = $conn->query("SELECT * FROM members");
$groups_result = $conn->query("SELECT * FROM groups");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Groups</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Admin - Manage Groups</h1>
    </header>
    <main>
        <section id="create-group">
            <h2>Create Group</h2>
            <form method="POST">
                <label for="group_name">Group Name:</label>
                <input type="text" id="group_name" name="group_name" required>
                <label for="member_ids">Select Members:</label>
                <select id="member_ids" name="member_ids[]" multiple required>
                    <?php while ($member = $members_result->fetch_assoc()) { ?>
                        <option value="<?php echo $member['id']; ?>"><?php echo $member['name']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit">Create Group</button>
            </form>
        </section>
        <section id="existing-groups">
            <h2>Existing Groups</h2>
            <ul>
                <?php while ($group = $groups_result->fetch_assoc()) { ?>
                    <li><?php echo $group['name']; ?></li>
                <?php } ?>
            </ul>
        </section>
    </main>
</body>
</html>
