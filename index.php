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

// Insert new lesson if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_lesson'])) {
    // Get form data
    $title = $_POST['title'];
    $type = $_POST['type'];

    // Insert data into the lesson table
    $sql = "INSERT INTO lesson (title, type) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $type);

    if ($stmt->execute()) {
        echo "<p>New lesson added successfully!</p>";
    } else {
        echo "<p>Error adding lesson: " . $conn->error . "</p>";
    }

    $stmt->close();
}

// Handle delete functionality
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM lesson WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<p>Lesson deleted successfully!</p>";
    } else {
        echo "<p>Error deleting lesson: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// SQL query to fetch data from the "lesson" table
$sql = "SELECT id, title, type FROM lesson";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson List</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        table.lesson-table {
            width: 80%;
            margin: 20px auto;
            padding: 10px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table.lesson-table th, table.lesson-table td {
            padding: 12px;
            text-align: left;
        }

        table.lesson-table th {
            background-color: #4CAF50;
            color: white;
        }

        table.lesson-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table.lesson-table tr:hover {
            background-color: #ddd;
        }

        table.lesson-table td {
            border-bottom: 1px solid #ddd;
        }

        table.lesson-table td:last-child {
            border-bottom: none;
        }

        /* Button styling */
        .add-button {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px 15px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .add-button:hover {
            background-color: #45a049;
        }

        /* Form styling */
        .add-lesson-form-container {
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: rgba(0,0,0,0.3);
            justify-content: center;
            align-items: center;
            display: none;
            position: absolute;
        }
        .add-lesson-form {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .add-lesson-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .add-lesson-form button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-lesson-form button:hover {
            background-color: #45a049;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
        }

        .action-buttons a {
            padding: 5px 10px;
            background-color: #FFC107;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .action-buttons a.delete {
            background-color: #F44336;
        }

        .action-buttons a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <h1>Lesson List</h1>

    <!-- Display lessons in a table -->
    <?php
    if ($result->num_rows > 0) {
        echo '<table class="lesson-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Title</th>';
        echo '<th>Type</th>';
        echo '<th>Actions</th>';  // New column for actions
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['title']) . '</td>';
            echo '<td>' . htmlspecialchars($row['type']) . '</td>';
            echo '<td class="action-buttons">';
            // Delete button
            echo '<a href="?delete_id=' . $row['id'] . '" class="delete" onclick="return confirm(\'Are you sure you want to delete this lesson?\');">Delete</a>';
            // Update button (will redirect to an update page)
            echo '<a href="update.php?id=' . $row['id'] . '">Update</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo "No lessons found.";
    }
    ?>

    <!-- Add New Lesson Button -->
    <button class="add-button" onclick="toggleForm()">+ Add New Lesson</button>

    <!-- Add Lesson Form -->
    <div class="add-lesson-form-container">
        <div class="add-lesson-form" id="addLessonForm">
            <h2>Add New Lesson</h2>
            <form method="POST" action="">
                <input type="text" name="title" placeholder="Lesson Title" required>
                <input type="text" name="type" placeholder="Lesson Type" required>
                <button type="submit" name="add_lesson">Add Lesson</button>
            </form>
        </div>
    </div>

    <script>
        function toggleForm() {
            const form = document.querySelector('.add-lesson-form-container');
            form.style.display = form.style.display === 'flex' ? 'none' : 'flex';
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
