<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shaxrillo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch lesson data for the form
if (isset($_GET['id'])) {
    $lesson_id = $_GET['id'];
    $sql = "SELECT * FROM lesson WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lesson = $result->fetch_assoc();
    $stmt->close();
}

// Handle update
if (isset($_POST['update_lesson'])) {
    $update_id = $_POST['id'];
    $update_title = $_POST['title'];
    $update_type = $_POST['type'];

    $sql = "UPDATE lesson SET title = ?, type = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $update_title, $update_type, $update_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the main page after updating
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Lesson</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
            color: #4CAF50;
        }

        .update-form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .update-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .update-form h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .update-form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .update-form button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .update-form button:hover {
            background-color: #45a049;
        }

        .update-form a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
        }

        .update-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="update-form-container">
    <div class="update-form">
        <h2>Update Lesson</h2>

        <!-- Update form -->
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $lesson['id']; ?>">
            <input type="text" name="title" value="<?php echo htmlspecialchars($lesson['title']); ?>" required placeholder="Lesson Title">
            <input type="text" name="type" value="<?php echo htmlspecialchars($lesson['type']); ?>" required placeholder="Lesson Type">
            <button type="submit" name="update_lesson">Update Lesson</button>
        </form>

        <!-- Link to go back -->
        <a href="index.php">Back to Lesson List</a>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
