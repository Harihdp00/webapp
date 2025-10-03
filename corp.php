<?php
$servername = "webapp-db.cbis6kyi4m01.ap-south-1.rds.amazonaws.com";
$username   = "admin";
$password   = "adminpassword";
$dbname     = "corp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data securely when form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $age  = $_POST['age'] ?? '';
    $city = $_POST['city'] ?? '';

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO employees (name, age, city) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $age, $city);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>New record added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
}

// Fetch all rows
$result = $conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Project Website</title>
    <style>
        table { border-collapse: collapse; width: 60%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        form input { margin: 5px; }
    </style>
</head>
<body>
    <h2>Welcome to My Project Website!</h2>

    <form method="post">
        NAME: <input type="text" name="name" required>
        AGE: <input type="number" name="age" required>
        CITY: <input type="text" name="city" required>
        <input type="submit" value="Add Data">
    </form>

    <br><br>
    <table>
        <tr>
            <th>ID</th><th>NAME</th><th>AGE</th><th>CITY</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['age']) ?></td>
                    <td><?= htmlspecialchars($row['city']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No records found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
