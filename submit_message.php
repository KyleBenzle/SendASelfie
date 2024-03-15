<?php
// Define the path to the chat log file
$chatLogPath = __DIR__ . '/chatlog.txt';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve message and name from POST data
    $message = $_POST['message'] ?? '';
    $name = $_POST['name'] ?? 'No Name';

    // Sanitize input to allow only certain HTML tags for styling
    $allowedTags = '<b><i><u><strong><em><span>';
    $message = strip_tags($message, $allowedTags);

    // Additional sanitization can be done here, e.g., htmlspecialchars for extra safety
    // $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

    // Format the message with the user's name and current timestamp
    $formattedMessage = sprintf("[%s] %s: %s\n", date('Y-m-d H:i:s'), $name, $message);

    // Append the message to the chat log file
    file_put_contents($chatLogPath, $formattedMessage, FILE_APPEND | LOCK_EX);

    // Respond to the request (optional)
    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully.']);
} else {
    // Handle non-POST requests here
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}


