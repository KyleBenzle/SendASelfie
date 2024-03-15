<?php
// Define the path to the chat log file
$chatLogPath = __DIR__ . '/chatlog.txt';

// Check if the chat log file exists
if (file_exists($chatLogPath)) {
    // Read and return the content of the chat log file
    $chatLogContent = file_get_contents($chatLogPath);
    echo json_encode(['status' => 'success', 'content' => $chatLogContent]);
} else {
    // If the chat log file doesn't exist, return an error message
    echo json_encode(['status' => 'error', 'message' => 'Chat log file not found.']);
}


