<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoom Reservation System For User</title>
    <link rel="stylesheet" href="fullcalendar/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Chatbox styles */
        #chatContainer {
            position: relative;
            /* Ensure the container is positioned */
            z-index: 9999;
            /* Set a high z-index to bring it to the front */
        }

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

        /* Calendar styles */
        #calendarContainer {
            margin-top: 50px;
            width: 80%;
            margin: 0 auto;
            text-align: center;
            font-size: 12px;
        }

        .form-group {
            padding: 3px;
            font-size: 16px;
        }

        /* Add more styles as needed */
    </style>
</head>

<body>
    <!-- Chatbox Container -->
    <div id="chatContainer">
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
    </div>

    <!-- Calendar Container -->
    <div id="calendarContainer">
        <h2>ZOOM RESERVATION SYSTEM</h2>
        <div id="calendar"></div>
    </div>

    <!-- Event Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Add New Zoom Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="u_status" class="control-label"><strong>Status</strong></label>
                        <input type="text" class="form-control" id="u_status" value="Pending" readonly>
                    </div>
                    <div class="form-group">
                        <label for="u_division" class="control-label"><strong>Division</strong></label>
                        <textarea type="text" name="u_division" id="u_division" class="form-control rounded-0" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="u_title" class="control-label"><strong>Title</strong></label>
                        <textarea type="text" name="u_title" id="u_title" class="form-control rounded-0" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="u_schedule_start" class="control-label"><strong>Schedule Start</strong></label>
                        <input type="datetime-local" class="form-control" id="u_schedule_start">
                    </div>
                    <div class="form-group">
                        <label for="u_schedule_end" class="control-label"><strong>Schedule End</strong></label>
                        <input type="datetime-local" class="form-control" id="u_schedule_end">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveEvent">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="fullcalendar/fullcalendar.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.js"></script>
    <script>
        // Chatbox JavaScript
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
    <script>
        // Calendar JavaScript
        $(document).ready(function() {
            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultView: 'month', // Start with month view
                editable: true,
                events: "fetch-event.php",
                displayEventTime: true, // Show event times
                eventRender: function(event, element, view) {
                    if (event.allDay === 'true' || event.allDay === true) {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    // Open the event modal when selecting a date range on the calendar
                    $('#eventModal').modal('show');
                    // Populate the start and end time fields in the event modal
                    $('#u_schedule_start').val(moment(start).format('YYYY-MM-DDTHH:mm'));
                    $('#u_schedule_end').val(moment(end).format('YYYY-MM-DDTHH:mm'));
                },
                eventDrop: function(event, delta) {
                    var start = event.start.format("YYYY-MM-DD HH:mm:ss");
                    var end = (event.end == null) ? start : event.end.format("YYYY-MM-DD HH:mm:ss");
                    $.ajax({
                        url: 'edit-event.php',
                        data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function(response) {
                            displayMessage("Updated Successfully");
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", error);
                            alert("An error occurred while updating the event: " + xhr.responseText);
                        }
                    });
                },
                eventClick: function(event) {
                    var deleteMsg = confirm("Do you really want to delete?");
                    if (deleteMsg) {
                        $.ajax({
                            type: "POST",
                            url: "delete-event.php",
                            data: "&id=" + event.id,
                            success: function(response) {
                                if (parseInt(response) > 0) {
                                    $('#calendar').fullCalendar('removeEvents', event.id);
                                    displayMessage("Deleted Successfully");
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("AJAX Error:", error);
                                alert("An error occurred while deleting the event: " + xhr.responseText);
                            }
                        });
                    }
                }
            });

            function displayMessage(message) {
                $(".response").html("<div class='success'>" + message + "</div>");
                setTimeout(function() {
                    $(".success").fadeOut();
                }, 3000);
            }

            // Save event button click handler
            $('#saveEvent').on('click', function() {
                // Get event details from the form
                var title = $('#u_title').val();
                var division = $('#u_division').val();
                var schedule_start = $('#u_schedule_start').val();
                var schedule_end = $('#u_schedule_end').val();

                // Perform validation here if needed

                // Submit event details to the server
                $.ajax({
                    url: 'add-event.php',
                    type: 'POST',
                    data: {
                        u_title: title,
                        u_division: division,
                        u_schedule_start: schedule_start,
                        u_schedule_end: schedule_end
                    },
                    success: function(data) {
                        // Refresh events after adding
                        calendar.fullCalendar('refetchEvents');
                        $('#eventModal').modal('hide');
                        displayMessage("Added Successfully");
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        alert("An error occurred while adding the event: " + xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>