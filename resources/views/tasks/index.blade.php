<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel To-Do List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>PHP - Simple To Do List App</h2>

        <!-- Form to add new task -->
        <form id="addTaskForm" class="input-group mb-3">
            <input type="text" id="taskInput" name="task" class="form-control" placeholder="Add Task" required>
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Add Task</button>
            </div>
        </form>

        <button id="showAllTasks" class="btn btn-primary mb-4">Show All Tasks</button>

        <!-- Task List Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="taskTable">
                @foreach($tasks as $key => $task)
                <tr id="task-{{ $task->id }}">
                    <td>{{ ++$key }}</td>
                    <td>{{ $task->task }}</td>
                    <td>{{ $task->status }}</td>
                    <td>
                        @if ($task->status == 'Pending')
                        <button class="btn btn-success btn-sm mark-done" data-id="{{ $task->id }}">✔</button>
                        @endif
                        <button class="btn btn-danger btn-sm delete-task" data-id="{{ $task->id }}">✖</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            // Handle task form submission with AJAX
            $('#addTaskForm').on('submit', function (e) {
                e.preventDefault();
                var task = $('#taskInput').val();
                $.ajax({
                    url: '{{ url("/task") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        task: task
                    },
                    success: function (response) {
                        // Append new task to the table
                        if(response.Status == 'OK'){
                            var n = $('#taskTable').find('tr').length;
                            n = n+1;
                            $('#taskTable').append(`
                                <tr id="task-${response.id}">
                                    <td>`+n+`</td>
                                    <td>${response.task}</td>
                                    <td>${response.status}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm mark-done" data-id="${response.id}">✔</button>
                                        <button class="btn btn-danger btn-sm delete-task" data-id="${response.id}">✖</button>
                                    </td>
                                </tr>
                            `);
                            $('#taskInput').val(''); // Clear input field
                        }else{
                            alert('Duplicate Data');
                            return false;
                        }
                    }
                });
            });

            // Handle marking task as done using AJAX
            $(document).on('click', '.mark-done', function () {
                var taskId = $(this).data('id');
                $.ajax({
                    url: `/task/${taskId}/done`,
                    method: 'GET',
                    success: function () {
                        $(`#task-${taskId} td:nth-child(3)`).text('Done');
                        $(`#task-${taskId} .mark-done`).remove(); // Remove the "done" button
                        $(`#task-${taskId}`).remove();
                    }
                });
            });

            // Handle task deletion with AJAX
            $(document).on('click', '.delete-task', function () {
                if (confirm('Are you sure you want to delete this task?')) {
                    var taskId = $(this).data('id');
                    $.ajax({
                        url: `/task/${taskId}/delete`,
                        method: 'GET',
                        success: function () {
                            $(`#task-${taskId}`).remove(); // Remove task row from the table
                        }
                    });
                }
            });

            $('#showAllTasks').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ url("/alltask") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Append new task to the table
                        var j =0;
                        $('#taskTable').html('');
                        jQuery.each(response.task, function(i, val) {
                            console.log(i,val);
                            j+=1;
                        $('#taskTable').append(`
                            <tr id="task-${val.id}">
                                <td>`+j+`</td>
                                <td>${val.task}</td>
                                <td>${val.status}</td>
                                <td>
                                    <button class="btn btn-success btn-sm mark-done" data-id="${val.id}">✔</button>
                                    <button class="btn btn-danger btn-sm delete-task" data-id="${val.id}">✖</button>
                                </td>
                            </tr>
                        `);
                        $('#taskInput').val(''); // Clear input field
                        });
                       
                    }
                });
            });
        });
    </script>
</body>
</html>
