<?php
require_once "db.php";

// Check if all required parameters are set
if (isset($_POST['u_id'], $_POST['u_status'], $_POST['u_division'], $_POST['u_title'], $_POST['u_scheduleStart'], $_POST['u_scheduleEnd'])) {
    // Sanitize and assign values
    $u_id = $_POST['u_id']; // No need for mysqli_real_escape_string when using prepared statements
    $u_status = $_POST['u_status'];
    $u_division = $_POST['u_division'];
    $u_title = $_POST['u_title'];
    $u_scheduleStart = $_POST['u_scheduleStart'];
    $u_scheduleEnd = $_POST['u_scheduleEnd'];

    // Prepare the SQL update statement
    $sqlUpdate = "UPDATE tbl_user_events SET u_status=?, u_division=?, u_title=?, u_scheduleStart=?, u_scheduleEnd=? WHERE u_id=?";
    $stmt = $conn->prepare($sqlUpdate);

    // Bind parameters
    $stmt->bind_param("sssssi", $u_status, $u_division, $u_title, $u_scheduleStart, $u_scheduleEnd, $u_id);

    // Execute the update statement
    if ($stmt->execute()) {
        // Return success message or any other desired response
        echo "Event updated successfully.";
    } else {
        // Error handling
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    // Handle case where required parameters are missing
    echo "Error: Required parameters are missing.";
}

// Close database connection (if needed)
$conn->close();
