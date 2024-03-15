<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Chat Application</title>
    <style>
        #chat-box {
            height: 300px;
            border: 1px solid #ccc;
            overflow-y: auto;
            padding: 8px;
            margin-bottom: 10px;
        }
        #message-input, #user-name {
            width: calc(100% - 20px);
            margin-bottom: 10px;
        }
    </style>
  </head>
  <body>
    <div id="chat-container"> <input id="user-name" placeholder="Your Name" type="text">
      <textarea id="message-input" placeholder="Type your message here..."></textarea>
      <button id="send-message">Send Message</button>
      <div id="text-styling-options">
        <!-- Text Styling Options Here --> </div>
      <button id="attach-selfie">Open Camera</button> <button id="capture">Take
        Picture</button> <video id="webcam" autoplay="autoplay" style="display:none;"
        hidden=""></video> <canvas id="canvas" style="display:none;" hidden=""></canvas>
    </div>
    <div id="chat-box"></div>
    ---
    
    <div id="chat-display"></div>
    <!-- Container to display the chat log -->
    <script>
        document.getElementById('send-message').addEventListener('click', function() {
            var userName = document.getElementById('user-name').value;
            var message = document.getElementById('message-input').value;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'submit_message.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Refresh the chat log to display the new message
                    loadChatLog();
                    console.log('Message sent:', xhr.responseText);
                } else {
                    console.error('Error sending message');
                }
            };
            xhr.send('name=' + encodeURIComponent(userName) + '&message=' + encodeURIComponent(message));
        });

        function loadChatLog() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'chatlog.txt', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('chat-display').innerHTML = xhr.responseText;
                } else {
                    console.error('Failed to load chat log');
                }
            };
            xhr.send();
        }

        // Load the chat log when the page loads
        window.onload = function() {
            loadChatLog();
        };
    </script> ----
    <script>
document.getElementById('send-message').addEventListener('click', function() {
    var message = document.getElementById('message-input').value;
    var name = document.getElementById('user-name').value || 'Anonymous'; // Default to 'Anonymous' if no name is provided

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'submit_message.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Message sent successfully:', xhr.responseText);
            document.getElementById('message-input').value = ''; // Clear the message input field
            // Optionally, fetch and display the updated chat log here
        } else {
            console.error('Error sending message:', xhr.responseText);
        }
    };
    xhr.send('name=' + encodeURIComponent(name) + '&message=' + encodeURIComponent(message));
});

document.getElementById('attach-selfie').addEventListener('click', function() {
    navigator.mediaDevices.getUserMedia({ video: true })
    .then(function(stream) {
        document.getElementById('webcam').srcObject = stream;
        document.getElementById('webcam').style.display = 'block'; // Show the video element to the user
        // Add a button or mechanism to capture the image from the video stream
    })
    .catch(function(error) {
        console.error("Error accessing the webcam", error);
    });
    // Implement the function to capture an image from the video and upload it
    // This requires additional JavaScript to handle capturing the image, converting it to a Blob, and uploading it
});
      
document.getElementById('capture').addEventListener('click', function() {
    var video = document.getElementById('webcam');
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convert canvas to blob and upload
    canvas.toBlob(function(blob) {
        var formData = new FormData();
        formData.append('image', blob, 'selfie.png');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'upload_selfie.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Image uploaded successfully');
            } else {
                console.error('Upload failed:', xhr.responseText);
            }
        };
        xhr.send(formData);
    }, 'image/png');
});

</script>
  </body>
</html>
