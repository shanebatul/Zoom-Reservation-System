<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoom Reservation System Final</title>
    <link rel="stylesheet" href="fullcalendar/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="fullcalendar/lib/jquery.min.js"></script>
    <script src="fullcalendar/lib/moment.min.js"></script>
    <script src="fullcalendar/fullcalendar.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title', // Display month and year in the center
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                events: "fetch-event.php",
                displayEventTime: false,
                eventRender: function(event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    openEventDialog(start, end, allDay);
                },

                editable: true,
                eventDrop: function(event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url: 'edit-event.php',
                        data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function(response) {
                            displayMessage("Updated Successfully");
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
                            }
                        });
                    }
                }
            });
        });

        function openEventDialog(start, end, allDay) {
            $("#eventModal").modal("show");
            $("#eventModal").on("hidden.bs.modal", function() {
                $("#u_title").val("");
                $("#u_division").val("");
                $("#u_scheduleStart").val("");
                $("#u_scheduleEnd").val("");
            });

            $("#saveEvent").click(function() {
                var title = $("#u_title").val();
                var division = $("#u_division").val();
                var scheduleStart = $("#u_scheduleStart").val();
                var scheduleEnd = $("#u_scheduleEnd").val();

                console.log("Title: ", title);
                console.log("Division: ", division);
                console.log("Start: ", scheduleStart);
                console.log("End: ", scheduleEnd);

                if (title && division && scheduleStart && scheduleEnd) {
                    var startFormatted = moment(scheduleStart).format("YYYY-MM-DD HH:mm:ss");
                    var endFormatted = moment(scheduleEnd).format("YYYY-MM-DD HH:mm:ss");

                    $.ajax({
                        url: 'add-event.php',
                        data: {
                            u_status: "Pending",
                            u_division: division,
                            u_title: title,
                            u_scheduleStart: startFormatted,
                            u_scheduleEnd: endFormatted
                        },
                        type: "POST",
                        success: function(data) {
                            console.log("Success: ", data);
                            displayMessage("Added Successfully");
                            $('#calendar').fullCalendar('refetchEvents');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error: ", xhr.responseText);
                            displayMessage("Error: Unable to save event. Please try again.");
                        },
                        complete: function() {
                            $("#eventModal").modal("hide");
                            calendar.fullCalendar('unselect');
                        }
                    });
                } else {
                    displayMessage("Error: Please fill in all fields.");
                }
            });
        }

        function displayMessage(message) {
            $(".response").html("<div class='success'>" + message + "</div>");
            setInterval(function() {
                $(".success").fadeOut();
            }, 1000);
        }
    </script>

    <style>
        body {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
        }

        #calendar {
            width: 700px;
            margin: 0 auto;
        }

        .response {
            height: 60px;
        }

        .success {
            background: #cdf3cd;
            padding: 10px 60px;
            border: #c3e6c3 1px solid;
            display: inline-block;
        }

        /* Center the month and year */
        .fc-toolbar h2 {
            text-align: center;
            margin-bottom: 0;
            /* Remove the margin */
            margin-top: 0;
            /* Remove the top margin */
        }
    </style>
</head>

<body>
    <h2>ZOOM RESERVATION SYSTEM</h2>

    <div class="response"></div>
    <div id='calendar'></div>

    <!-- Event Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Add New Zoom MeetingSchedule</h5>
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
                        <label for="eventTitle" class="control-label" style="font-size: 14px;"><strong>Division</strong></label>
                        <textarea type="4" name="u_division" id="u_division" class="form-control form-control-sm rounded-0" style="font-size: 14px;" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="eventTitle" class="control-label" style="font-size: 14px;"><strong>Title</strong></label>
                        <textarea type="4" name="u_title" id="u_title" class="form-control form-control-sm rounded-0" style="font-size: 14px;" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="u_scheduleStart" class="control-label" style="font-size: 14px;"><strong>Schedule Start</strong></label>
                        <input type="datetime-local" class="form-control" id="u_scheduleStart">
                    </div>
                    <div class="form-group">
                        <label for="u_scheduleEnd" class="control-label" style="font-size: 14px;"><strong>Schedule End</strong></label>
                        <input type="datetime-local" class="form-control" id="u_scheduleEnd">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveEvent">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>