<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "zoom_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events from the database
$sql = "SELECT u_id, u_status, u_division, u_title, u_schedule_start, u_schedule_end FROM tbl_user_events";
$result = $conn->query($sql);

// Prepare array to hold events
$events = array();

// Check if there are any events
if ($result->num_rows > 0) {
    // Loop through each row of data
    while ($row = $result->fetch_assoc()) {
        // Format the event data
        $event = array(
            'u_id' => $row['u_id'],
            'u_status' => $row['u_status'],
            'u_division' => $row['u_division'],
            'u_title' => $row['u_title'],
            'u_start' => date('Y-m-d H:i:s', strtotime($row['u_scheduleStart'])),
            'u_end' => date('Y-m-d H:i:s', strtotime($row['u_scheduleEnd'])),
            'u_allDay' => false, // Assuming all events are not all-day events
            // Add more fields if needed
        );

        // Add the event to the events array
        $events[] = $event;
    }
}

// Close the database connection
$conn->close();

// Return events as JSON data
header('Content-Type: application/json');
echo json_encode($events);
