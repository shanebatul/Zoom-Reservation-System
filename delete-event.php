<?php
require_once "db.php";

// Check if the 'id' parameter is set
if (isset($_POST['u_id'])) {
    // Sanitize the 'id' parameter to prevent SQL injection
    $u_id = $_POST['u_id']; // No need for mysqli_real_escape_string when using prepared statements

    // Prepare the delete statement
    $sqlDelete = "DELETE FROM tbl_user_events WHERE u_id = ?";
    $stmt = $conn->prepare($sqlDelete);

    // Bind parameters
    $stmt->bind_param("i", $u_id); // Assuming u_id is an integer, adjust the "i" accordingly if it's another data type

    // Execute the delete statement
    if ($stmt->execute()) {
        // Check the number of affected rows
        echo $stmt->affected_rows; // Return the number of affected rows
    } else {
        // Error handling
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    // Handle case where the 'id' parameter is missing
    echo "Error: 'id' parameter is missing.";
}

// Close database connection (if needed)
$conn->close();
