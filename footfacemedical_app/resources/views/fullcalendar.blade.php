<!DOCTYPE html>
<html>
<head>
<title></title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">App Name</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/calendar') }}">Calendar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/info') }}">Info</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
<h2 class="text-success"></h2>
<div id='calendar'></div>
</div>
<script>
$(document).ready(function () {
    const SITEURL = "{{ url('/calendar') }}";
    const calendar = $('#calendar').fullCalendar({
        editable: true,
        events: `${SITEURL}/fullcalender`,
        displayEventTime: true,
        selectable: true,
        selectHelper: true,
        select: function (start, end) {
            const modal = ` 
                <div id="eventModal" class="modal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Event</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form id="eventForm">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" id="eventTitle">
                                    </div>
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <select class="form-control" id="eventStart">
                                            <option value="08:00">8:00</option>
                                            <option value="09:00">9:00</option>
                                            <option value="10:00">10:00</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <select class="form-control" id="eventEnd">
                                            <option value="08:00">8:00</option>
                                            <option value="09:00">9:00</option>
                                            <option value="10:00">10:00</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" id="saveEvent">Save</button>
                                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>`;

            $('body').append(modal);
            $('#eventModal').modal('show');

            $('#saveEvent').on('click', function () {
                const title = $('#eventTitle').val();
                const startTime = $('#eventStart').val();
                const endTime = $('#eventEnd').val();

                if (title && startTime && endTime) {
                    const start = moment(start).format("Y-MM-DD") + 'T' + startTime;
                    const end = moment(end).format("Y-MM-DD") + 'T' + endTime;

                    $.post(`${SITEURL}/fullcalenderAjax`, { title, start, end, type: 'add' }, function (data) {
                        calendar.fullCalendar('renderEvent', { id: data.id, title, start, end }, true);
                        calendar.fullCalendar('unselect');
                        $('#eventModal').modal('hide').remove();
                    });
                }
            });
        },
        eventDrop: function (event) {
            const start = moment(event.start).format("Y-MM-DD");
            const end = moment(event.end).format("Y-MM-DD");
            $.post(`${SITEURL}/fullcalenderAjax`, { id: event.id, title: event.title, start, end, type: 'update' });
        },
        eventClick: function (event) {
            if (confirm("Delete this event?")) {
                $.post(`${SITEURL}/fullcalenderAjax`, { id: event.id, type: 'delete' }, function () {
                    calendar.fullCalendar('removeEvents', event.id);
                });
            }
        }
    });
});
</script>
</body>
</html>