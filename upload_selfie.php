<?php
// Directory where uploaded images will be saved
$targetDir = __DIR__ . '/images/selfies/';
// Define allowed file types for upload
$allowedTypes = ['jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];

// Check if a file has been uploaded
if (isset($_FILES['image']['name'])) {
    $filename = basename($_FILES['image']['name']);
    $targetFilePath = $targetDir . $filename;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Verify file type is allowed
    if (array_key_exists($fileType, $allowedTypes)) {
        // Check if file already exists and generate a unique file name
        while (file_exists($targetFilePath)) {
            $filename = uniqid() . '.' . $fileType;
            $targetFilePath = $targetDir . $filename;
        }

        // Validate file size - let's limit it to 5MB
        if ($_FILES['image']['size'] < 5000000) {
            // Move the file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                // File upload success, append a reference to the chat log
                $chatLogPath = __DIR__ . '/chatlog.txt';
                $message = "<img src='images/selfies/{$filename}' />";
                $formattedMessage = sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $message);
                file_put_contents($chatLogPath, $formattedMessage, FILE_APPEND | LOCK_EX);

                echo json_encode(['status' => 'success', 'message' => 'Image uploaded successfully.']);
            } else {
                // File move failed
                echo json_encode(['status' => 'error', 'message' => 'Sorry, there was an error uploading your file.']);
            }
        } else {
            // File is too large
            echo json_encode(['status' => 'error', 'message' => 'Sorry, your file is too large.']);
        }
    } else {
        // Unsupported file type
        echo json_encode(['status' => 'error', 'message' => 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed.']);
    }
} else {
    // No file uploaded
    echo json_encode(['status' => 'error', 'message' => 'No file was uploaded.']);
}

if (isset($_FILES['image'])) {
    // Error handling, file validation, and saving logic here
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
        echo json_encode(['status' => 'success', 'message' => 'File uploaded successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded.']);
}

