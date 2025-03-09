<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thrift Contribution Management System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Thrift Contribution Management System</h1>
    </header>
    <main>
        <section id="registration">
            <h2>Member Registration</h2>
            <form id="registration-form" method="POST" action="register.php">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Register</button>
            </form>
        </section>
    </main>
    <script src="js/scripts.js"></script>
</body>
</html>
