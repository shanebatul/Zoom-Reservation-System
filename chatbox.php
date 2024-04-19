<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbox with Real-Time Chat</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        #chatSymbol {
            position: fixed;
            bottom: 20px;
            right: 20px;
            cursor: pointer;
            width: 50px;
            height: 50px;
            background-image: url(images/chat-icon.jpg);
            /* Replace 'chat-icon.jpg' with the path to your image */
            background-size: cover;
        }

        #messenger {
            display: none;
            /* Initially hidden */
            position: fixed;
            bottom: 0;
            right: 20px;
            width: 300px;
            height: 400px;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        #messengerHeader {
            background-color: #ccc;
            color: #333;
            padding: 10px;
            font-weight: bold;
            border-bottom: 1px solid #999;
        }

        #messages {
            max-height: 150px;
            /* Reduced height to accommodate the header */
            overflow-y: scroll;
            padding: 10px;
            overflow-x: hidden;
            /* Hide horizontal scrollbar */
            overflow-y: auto;
            /* Add vertical scrollbar only when needed */
        }

        #messageInputContainer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-top: auto;
            /* Push to the bottom */
        }

        #messageInput {
            width: calc(70% - 10px);
            /* Adjusted for padding */
            padding: 10px;
            border: none;
            border-top: 1px solid #ccc;
            box-sizing: border-box;
        }

        #sendButton {
            width: calc(30% - 10px);
            /* Adjusted for padding */
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 20px;
            line-height: 20px;
            text-align: center;
            border-radius: 5px;
        }

        #exitButton {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 25px;
            height: 25px;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 20px;
            line-height: 20px;
            text-align: center;
            border-radius: 5px;
            padding: 0;
        }
    </style>
</head>

<body>
    <!-- Chat Symbol -->
    <div id="chatSymbol" onclick="toggleMessenger()"></div>

    <!-- Mini Messenger -->
    <div id="messenger">
        <div id="messengerHeader">Chatbox</div>
        <div id="messages"></div>
        <div id="messageInputContainer">
            <input type="text" id="messageInput" placeholder="Type your message...">
            <button id="sendButton" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
        </div>
        <button id="exitButton" onclick="exitMessenger()">X</button>
    </div>

    <script>
        const socket = io();

        socket.on('connect', function() {
            // Send a message to the server indicating that the admin has connected
            socket.emit('admin connected');
        });

        socket.on('chat message', function(msg) {
            const item = document.createElement('div');
            item.textContent = msg;
            document.getElementById('messages').appendChild(item);
        });

        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (message !== '') {
                socket.emit('admin message', message);
                messageInput.value = '';
            }
        }

        function toggleMessenger() {
            var messenger = document.getElementById("messenger");
            if (messenger.style.display === "none") {
                messenger.style.display = "flex"; /* Change to flex */
            } else {
                messenger.style.display = "none";
            }
        }

        function exitMessenger() {
            var messenger = document.getElementById("messenger");
            messenger.style.display = "none";
        }

        // Ensure chatbox is hidden initially after page reload
        window.onload = function() {
            var messenger = document.getElementById("messenger");
            messenger.style.display = "none";
        };
    </script>
</body>

</html>