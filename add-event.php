<?php
require_once "db.php";

// Check if all required parameters are set
if (
    !isset($_POST['u_status']) || !isset($_POST['u_division']) || !isset($_POST['u_title']) || !isset($_POST['u_scheduleStart']) || !isset($_POST['u_scheduleEnd'])
) {
    // Set appropriate status code and return JSON error message
    http_response_code(400);
    echo json_encode(array("error" => "Required parameters are missing."));
    exit;
}

// Initialize and assign values to variables
$u_status = htmlspecialchars($_POST['u_status']);
$u_division = htmlspecialchars($_POST['u_division']);
$u_title = htmlspecialchars($_POST['u_title']);
$u_scheduleStart = htmlspecialchars($_POST['u_scheduleStart']);
$u_scheduleEnd = htmlspecialchars($_POST['u_scheduleEnd']);

// Validate input lengths (example for title)
$maxTitleLength = 100;
if (strlen($u_title) > $maxTitleLength) {
    http_response_code(400);
    echo json_encode(array("error" => "Title exceeds maximum length."));
    exit;
}

// Validate data format for date-time values
if (!strtotime($u_scheduleStart) || !strtotime($u_scheduleEnd)) {
    http_response_code(400);
    echo json_encode(array("error" => "Invalid date-time format."));
    exit;
}

// Output the format of u_scheduleStart and u_scheduleEnd for debugging
echo "u_scheduleStart format: " . date("Y-m-d H:i:s", strtotime($u_scheduleStart)) . "<br>";
echo "u_scheduleEnd format: " . date("Y-m-d H:i:s", strtotime($u_scheduleEnd)) . "<br>";

// Perform SQL insertion using prepared statement
$sqlInsert = "INSERT INTO tbl_user_events (u_status, u_division, u_title, u_scheduleStart, u_scheduleEnd) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sqlInsert);
$stmt->bind_param("sssss", $u_status, $u_division, $u_title, $u_scheduleStart, $u_scheduleEnd);

// Execute the prepared statement
if ($stmt->execute()) {
    // Insertion successful, return success message
    echo json_encode(array("message" => "Event added successfully."));
} else {
    // Error handling with database error message
    http_response_code(500);
    echo json_encode(array("error" => "Error adding event: " . $conn->error));
}

// Close prepared statement
$stmt->close();
