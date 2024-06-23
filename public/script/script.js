// Key Lending Script
if(document.getElementById('keyActionModal')) {
    // Handle click on lend button
    var lendButtons = document.querySelectorAll('.lend-btn');
    lendButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var scheduleId = this.getAttribute('data-schedule-id');
            document.getElementById('scheduleId').value = scheduleId;
            document.getElementById('actionType').value = 'start';
            document.getElementById('modalTitle').textContent = 'Lend Key Action';
            document.getElementById('modalMessage').textContent = 'Please enter your password to lend the key:';
            var actionUrl = '/key-lending/' + scheduleId + '/verify-update-start';
            document.getElementById('keyActionForm').setAttribute('action', actionUrl);
            document.getElementById('keyActionModal').style.display = 'block';
        });
    });

    // Handle click on return button
    var returnButtons = document.querySelectorAll('.return-btn');
    returnButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var scheduleId = this.getAttribute('data-schedule-id');
            document.getElementById('scheduleId').value = scheduleId;
            document.getElementById('actionType').value = 'end';
            document.getElementById('modalTitle').textContent = 'Return Key Action';
            document.getElementById('modalMessage').textContent = 'Please enter your password to return the key:';
            var actionUrl = '/key-lending/' + scheduleId + '/verify-update-end';
            document.getElementById('keyActionForm').setAttribute('action', actionUrl);
            document.getElementById('keyActionModal').style.display = 'block';
        });
    });

    // Handle click on close button or outside modal
    var closeButtons = document.querySelectorAll('.close-modal, .modal .close, .modal-overlay');
    closeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('keyActionModal').style.display = 'none';
            document.querySelector('#keyActionModal input[type="password"]').value = '';
            var alerts = document.querySelectorAll('#keyActionModal .alert');
            alerts.forEach(function (alert) {
                alert.parentNode.removeChild(alert);
            });
        });
    });
};


// Schedule Script
if(document.getElementById('createModalSchedule') || document.getElementById('editModalSchedule')) {
    // Get the modal elements
    var createModalSchedule = document.getElementById("createModalSchedule");
    var editModalSchedule = document.getElementById("editModalSchedule");
    var editModalScheduleBatch = document.getElementById("editModalScheduleBatch");

    // Get the <span> elements that close the modals
    var closecreateModalSchedule = createModalSchedule.getElementsByClassName("close")[0];
    var closeeditModalSchedule = editModalSchedule.getElementsByClassName("close")[0];
    var closeeditModalScheduleBatch = editModalScheduleBatch.getElementsByClassName("close")[0];

    // Open create modal on button click
    document.getElementById('opencreateModalSchedule').onclick = function() {
        createModalSchedule.style.display = "block";
    };

    // Open edit modal on edit button click
    document.querySelectorAll('.edit-button-schedule').forEach(function(button) {
        button.onclick = function() {
            var scheduleId = this.getAttribute('data-id');
            var date = this.getAttribute('data-date');
            var start = this.getAttribute('data-start');
            var end = this.getAttribute('data-end');
            var assignmentId = this.getAttribute('data-assignment-id');
            var roomId = this.getAttribute('data-room-id');
            console.log(scheduleId);

            document.getElementById('modalDate').value = date;
            document.getElementById('modalStart').value = start;
            document.getElementById('modalEnd').value = end;
            document.getElementById('modalAssignment').value = assignmentId;
            document.getElementById('modalRoom').value = roomId;
            document.getElementById('editForm').action = 'schedule/' + scheduleId + '/edit';

            editModalSchedule.style.display = "block";
        };
    });

    document.querySelectorAll('.batch-edit-button-schedule').forEach(function(button) {
        button.onclick = function() {
            var scheduleIds = this.getAttribute('data-ids').split(',');
            var dates = this.getAttribute('data-dates').split(',');
            var start = this.getAttribute('data-start');
            var end = this.getAttribute('data-end');
            var assignmentId = this.getAttribute('data-assignment-id');
            var roomId = this.getAttribute('data-room-id');
            console.log(typeof(scheduleIds));
            console.log(scheduleIds);
            console.log(start);

            scheduleIds.forEach(function(id, index) {
            var inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'ids[]';
            inputId.value = id;
            idsContainer.appendChild(inputId);

            var inputDate = document.createElement('input');
            inputDate.type = 'hidden';
            inputDate.name = 'dates[]';
            inputDate.value = dates[index];
            datesContainer.appendChild(inputDate);
        });

            // document.getElementById('modalIds').value = scheduleIds;
            // document.getElementById('modalDates').value = dates;
            document.getElementById('modalStartBatch').value = start;
            document.getElementById('modalEndBatch').value = end;
            document.getElementById('modalAssignmentBatch').value = assignmentId;
            document.getElementById('modalRoomBatch').value = roomId;
            document.getElementById('editFormBatch').action = 'schedule/batch-edit?ids=' + scheduleIds.join(',');

            editModalScheduleBatch.style.display = "block";
            console.log('test');
        };
    });

    // When the user clicks on <span> (x), close the create modal
    closecreateModalSchedule.onclick = function() {
        createModalSchedule.style.display = "none";
    };

    // When the user clicks on <span> (x), close the edit modal
    closeeditModalSchedule.onclick = function() {
        editModalSchedule.style.display = "none";
    };

    closeeditModalScheduleBatch.onclick = function() {
        editModalScheduleBatch.style.display = "none";
    };

    // When the user clicks anywhere outside of the modals, close them
    window.onclick = function(event) {
        if (event.target == createModalSchedule) {
            createModalSchedule.style.display = "none";
        }
        if (event.target == editModalSchedule) {
            editModalSchedule.style.display = "none";
        }
        if (event.target == editModalScheduleBatch) {
            editModalScheduleBatch.style.display = "none";
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.expand-button').forEach(button => {
            button.addEventListener('click', function() {
                const expandedContent = this.closest('tr').nextElementSibling;
                if (expandedContent.style.display === 'none' || !expandedContent.style.display) {
                    expandedContent.style.display = 'table-row';
                    this.textContent = 'Collapse';
                } else {
                    expandedContent.style.display = 'none';
                    this.textContent = 'Expand';
                }
            });
        });
    });
};


    // Assignment Script
if(document.getElementById('createModalAssignment') || document.getElementById('editModalAssignment')) {
        // Get the modal elements
        var createModalAssignment = document.getElementById("createModalAssignment");
        var editModalAssignment = document.getElementById("editModalAssignment");

        // Get the <span> elements that close the modals
        var closecreateModalAssignment = createModalAssignment.getElementsByClassName("close")[0];
        var closeeditModalAssignment = editModalAssignment.getElementsByClassName("close")[0];

        // Open create modal on button click
        document.getElementById('opencreateModalAssignment').onclick = function() {
            createModalAssignment.style.display = "block";
        };

        // Open edit modal on edit button click
        document.querySelectorAll('.edit-button-assignment').forEach(function(button) {
            button.onclick = function() {
                var assignmentId = this.getAttribute('data-id');
                var userId = this.getAttribute('data-user_id');
                var kelasId = this.getAttribute('data-kelas_id');

                document.getElementById('modalUser').value = userId;
                document.getElementById('modalClass').value = kelasId;
                document.getElementById('editForm').action = 'assignment/' + assignmentId + '/edit';

                editModalAssignment.style.display = "block";
            };
        });

        // When the user clicks on <span> (x), close the create modal
        closecreateModalAssignment.onclick = function() {
            createModalAssignment.style.display = "none";
        };

        // When the user clicks on <span> (x), close the edit modal
        closeeditModalAssignment.onclick = function() {
            editModalAssignment.style.display = "none";
        };

        // When the user clicks anywhere outside of the modals, close them
        window.onclick = function(event) {
            if (event.target == createModalAssignment) {
                createModalAssignment.style.display = "none";
            }
            if (event.target == editModalAssignment) {
                editModalAssignment.style.display = "none";
            }
        };
    };

    // Subject Script
if(document.getElementById('createModalSubject') || document.getElementById('editModalSubject')) {
        // Get the modal elements
        var createModalSubject = document.getElementById("createModalSubject");
        var editModalSubject = document.getElementById("editModalSubject");

        // Get the <span> elements that close the modals
        var closecreateModalSubject = createModalSubject.getElementsByClassName("close")[0];
        var closeeditModalSubject = editModalSubject.getElementsByClassName("close")[0];

        // Open create modal on button click
        document.getElementById('opencreateModalSubject').onclick = function() {
            createModalSubject.style.display = "block";
        };

        // Open edit modal on edit button click
        document.querySelectorAll('.edit-button-subject').forEach(function(button) {
            button.onclick = function() {
                var subjectId = this.getAttribute('data-id');
                var subjectName = this.getAttribute('data-name');

                document.getElementById('modalSubjectId').value = subjectId;
                document.getElementById('modalSubjectName').value = subjectName;
                document.getElementById('editForm').action = 'subject/' + subjectId + '/edit';

                editModalSubject.style.display = "block";
            };
        });

        // When the user clicks on <span> (x), close the create modal
        closecreateModalSubject.onclick = function() {
            createModalSubject.style.display = "none";
        };

        // When the user clicks on <span> (x), close the edit modal
        closeeditModalSubject.onclick = function() {
            editModalSubject.style.display = "none";
        };

        // When the user clicks anywhere outside of the modals, close them
        window.onclick = function(event) {
            if (event.target == createModalSubject) {
                createModalSubject.style.display = "none";
            }
            if (event.target == editModalSubject) {
                editModalSubject.style.display = "none";
            }
        };
    };

    // Room Script
if(document.getElementById('createModalRoom') || document.getElementById('editModalRoom')) {
        // Get the modal elements
        var createModalRoom = document.getElementById("createModalRoom");
        var editModalRoom = document.getElementById("editModalRoom");

        // Get the <span> elements that close the modals
        var closecreateModalRoom = createModalRoom.getElementsByClassName("close")[0];
        var closeeditModalRoom = editModalRoom.getElementsByClassName("close")[0];

        // Open create modal on button click
        document.getElementById('opencreateModalRoom').onclick = function() {
            createModalRoom.style.display = "block";
        };

        // Open edit modal on edit button click
        document.querySelectorAll('.edit-button-room').forEach(function(button) {
            button.onclick = function() {
                var roomId = this.getAttribute('data-id');
                var roomNumber = this.getAttribute('data-room_number');

                document.getElementById('modalRoomId').value = roomId;
                document.getElementById('modalRoomNumber').value = roomNumber;
                document.getElementById('editForm').action = 'room/' + roomId + '/edit';

                editModalRoom.style.display = "block";
            };
        });

        // When the user clicks on <span> (x), close the create modal
        closecreateModalRoom.onclick = function() {
            createModalRoom.style.display = "none";
        };

        // When the user clicks on <span> (x), close the edit modal
        closeeditModalRoom.onclick = function() {
            editModalRoom.style.display = "none";
        };

        // When the user clicks anywhere outside of the modals, close them
        window.onclick = function(event) {
            if (event.target == createModalRoom) {
                createModalRoom.style.display = "none";
            }
            if (event.target == editModalRoom) {
                editModalRoom.style.display = "none";
            }
        };
    };

    // Class Script (Kelas Script)
if(document.getElementById('createModalClass') || document.getElementById('editModalClass')) {
        // Get the modal elements
        var createModalClass = document.getElementById("createModalClass");
        var editModalClass = document.getElementById("editModalClass");

        // Get the <span> elements that close the modals
        var closecreateModalClass = createModalClass.getElementsByClassName("close")[0];
        var closeeditModalClass = editModalClass.getElementsByClassName("close")[0];

        // Open create modal on button click
        document.getElementById('opencreateModalClass').onclick = function() {
            createModalClass.style.display = "block";
        };

        // Open edit modal on edit button click
        document.querySelectorAll('.edit-button-class').forEach(function(button) {
            button.onclick = function() {
                var classId = this.getAttribute('data-id');
                var prodi = this.getAttribute('data-prodi');
                var subjectId = this.getAttribute('data-subject');
                var classChar = this.getAttribute('data-class');

                document.getElementById('modalProdi').value = prodi;
                document.getElementById('modalSubject').value = subjectId;
                document.getElementById('modalClass').value = classChar;
                document.getElementById('editForm').action = 'class/' + classId + '/edit';

                editModalClass.style.display = "block";
            };
        });

        // When the user clicks on <span> (x), close the create modal
        closecreateModalClass.onclick = function() {
            createModalClass.style.display = "none";
        };

        // When the user clicks on <span> (x), close the edit modal
        closeeditModalClass.onclick = function() {
            editModalClass.style.display = "none";
        };

        // When the user clicks anywhere outside of the modals, close them
        window.onclick = function(event) {
            if (event.target == createModalClass) {
                createModalClass.style.display = "none";
            }
            if (event.target == editModalClass) {
                editModalClass.style.display = "none";
            }
        };
    };

    // User Script
if(document.getElementById('createModalUser') || document.getElementById('editModalUser')) {
        // Get the modal elements
        var createModalUser = document.getElementById("createModalUser");
        var editModalUser = document.getElementById("editModalUser");

        // Get the <span> elements that close the modals
        var closecreateModalUser = createModalUser.getElementsByClassName("close")[0];
        var closeeditModalUser = editModalUser.getElementsByClassName("close")[0];

        // Open create modal on button click
        document.getElementById('opencreateModalUser').onclick = function() {
            createModalUser.style.display = "block";
        };

        // Open edit modal on edit button click
        document.querySelectorAll('.edit-button-user').forEach(function(button) {
            button.onclick = function() {
                var userId = this.getAttribute('data-id');
                var userName = this.getAttribute('data-name');
                var userEmail = this.getAttribute('data-email');
                var userRole = this.getAttribute('data-role');

                document.getElementById('modalUserName').value = userName;
                document.getElementById('modalUserEmail').value = userEmail;
                document.getElementById('modalUserPassword').value = '';
                document.getElementById('editForm').action = 'user/' + userId + '/edit';

                if (userRole == 'Admin') {
                    document.getElementById('modalRoleAdmin').checked = true;
                } else if (userRole == 'Lecturer') {
                    document.getElementById('modalRoleLecturer').checked = true;
                } else if (userRole == 'Assistant') {
                    document.getElementById('modalRoleAssistant').checked = true;
                }

                editModalUser.style.display = "block";
            };
        });

        // When the user clicks on <span> (x), close the create modal
        closecreateModalUser.onclick = function() {
            createModalUser.style.display = "none";
        };

        // When the user clicks on <span> (x), close the edit modal
        closeeditModalUser.onclick = function() {
            editModalUser.style.display = "none";
        };

        // When the user clicks anywhere outside of the modals, close them
        window.onclick = function(event) {
            if (event.target == createModalUser) {
                createModalUser.style.display = "none";
            }
            if (event.target == editModalUser) {
                editModalUser.style.display = "none";
            }
        };
    };
