<?php
require_once "db.php";

// Initialize an array to hold events
$events = array();

// SQL query to fetch events from the database
$sqlQuery = "SELECT * FROM tbl_user_events ORDER BY u_id";

// Execute the query
$result = mysqli_query($conn, $sqlQuery);

// Check if the query was successful
if ($result) {
    // Loop through each row of data
    while ($row = mysqli_fetch_assoc($result)) {
        // Add the row to the events array
        $events[] = $row;
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    // Handle the case where the query failed
    $error = mysqli_error($conn);
    $events['error'] = "Error fetching events: $error";
}

// Close the database connection
mysqli_close($conn);

// Set the content type header to JSON
header('Content-Type: application/json');

// Encode the events array as JSON and echo it
echo json_encode($events);
