// Home Script
// Display current time
function updateClock() {
    var currentDate = document.getElementById('current-date');
    var currentTime = document.getElementById('current-time');

    if (currentDate && currentTime) {
        var now = new Date();
        var day = now.toLocaleDateString('en-US', { weekday: 'long' });
        var date = now.getDate();
        var month = now.toLocaleDateString('en-US', { month: 'long' });
        var year = now.getFullYear();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();

        // Pad single digit numbers with a leading zero
        date = (date < 10 ? '0' : '') + date;
        hours = (hours < 10 ? '0' : '') + hours;
        minutes = (minutes < 10 ? '0' : '') + minutes;
        seconds = (seconds < 10 ? '0' : '') + seconds;

        var currentDateString = day + ', ' + date + ' ' + month + ' ' + year;
        var currentTimeString =  hours + ':' + minutes + ':' + seconds;

        currentDate.textContent = currentDateString;
        currentTime.textContent = currentTimeString;
    }
}

// Update the clock every second
setInterval(updateClock, 1000);

// Initial call to display the clock immediately
updateClock();

// Home Section
function showContent(contentId, element) {
    // Hide all content elements
    var contentElements = document.getElementsByClassName('content');
    for (var i = 0; i < contentElements.length; i++) {
        contentElements[i].classList.remove('active-content');
    }

    // Show the selected content
    document.getElementById(contentId).classList.add('active-content');

    // Remove 'active-link' class from all nav links
    var navLinks = document.querySelectorAll('.section-navbar a');
    for (var j = 0; j < navLinks.length; j++) {
        navLinks[j].classList.remove('active-link');
    }

    // Add 'active-link' class to the clicked nav link
    element.classList.add('active-link');
}


// Key Lending Script
// Popup Open and Close Handling
if(document.getElementById('keyActionModal')) {
    keyModal = document.getElementById('keyActionModal');

    const editForm = document.getElementById('keyActionForm');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalMessageAdmin = document.getElementById('modalMessageAdmin');
    if (getComputedStyle(keyModal).display === 'block' || keyModal.classList.contains('open')) {
        const formAction = document.getElementById('modalLendAction');
        const modalTitleField = document.getElementById('modalTitleForm');
        const modalMsgField = document.getElementById('modalMsgForm');
        const modalMsgFieldAdmin = document.getElementById('modalMsgFormAdmin');

        modalTitleField.value = modalTitleField.getAttribute('data-old-value');
        modalTitle.textContent = modalTitleField.getAttribute('data-old-value');

        if (modalMessage) {
            modalMsgField.value = modalMsgField.getAttribute('data-old-value');
            modalMessage.textContent = modalMsgField.getAttribute('data-old-value');
        }

        if (modalMessageAdmin) {
            modalMsgFieldAdmin.value = modalMsgFieldAdmin.getAttribute('data-old-value');
            modalMessageAdmin.textContent = modalMsgFieldAdmin.getAttribute('data-old-value');
        }

        formAction.value = formAction.getAttribute('data-old-action');
        editForm.action = formAction.getAttribute('data-old-action');
        console.log(formAction.value);
    }

    // Handle click on lend button
    var lendButtons = document.querySelectorAll('.lend-btn');
    lendButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var scheduleId = this.getAttribute('data-schedule-id');
            document.getElementById('scheduleId').value = scheduleId;
            document.getElementById('actionType').value = 'start';

            document.getElementById('modalTitle').textContent = 'Lend Key';
            document.getElementById('modalTitleForm').value = 'Lend Key';
            modalTitle.value = 'Lend Key';

            if (document.getElementById('modalMessage')) {
                document.getElementById('modalMessage').textContent = 'Please enter your password to lend the key:';
                document.getElementById('modalMsgForm').value = 'Please enter your password to lend the key:';
                modalMessage.value = 'Please enter your password to lend the key:';
            }
            if (document.getElementById('modalMessageAdmin')) {
                document.getElementById('modalMessageAdmin').textContent = 'Are you sure you want to lend key?';
                document.getElementById('modalMsgFormAdmin').value = 'Are you sure you want to lend key?';
                modalMessageAdmin.value = 'Are you sure you want to lend key?';
            }

            var actionUrl = '/key-lending/' + scheduleId + '/verify-update-start';
            document.getElementById('modalLendAction').value = '/key-lending/' + scheduleId + '/verify-update-start';
            editForm.action = actionUrl;

            keyModal.style.display = 'block';
        });
    });

    // Handle click on return button
    var returnButtons = document.querySelectorAll('.return-btn');
    returnButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var scheduleId = this.getAttribute('data-schedule-id');
            document.getElementById('scheduleId').value = scheduleId;
            document.getElementById('actionType').value = 'end';

            document.getElementById('modalTitle').textContent = 'Return Key';
            document.getElementById('modalTitleForm').value = 'Return Key';
            modalTitle.value = 'Return Key';

            if (document.getElementById('modalMessage')) {
                document.getElementById('modalMessage').textContent = 'Please enter your password to return the key:';
                document.getElementById('modalMsgForm').value = 'Please enter your password to return the key:';
                modalMessage.value = 'Please enter your password to return the key:';
            }

            if (document.getElementById('modalMessageAdmin')) {
                document.getElementById('modalMessageAdmin').textContent = 'Are you sure you want to return key?';
                document.getElementById('modalMsgFormAdmin').value = 'Are you sure you want to return key?';
                modalMessageAdmin.value = 'Are you sure you want to return key?';
            }

            var actionUrl = '/key-lending/' + scheduleId + '/verify-update-end';
            document.getElementById('modalLendAction').value = '/key-lending/' + scheduleId + '/verify-update-end';
            editForm.action = actionUrl;

            keyModal.style.display = 'block';
        });
    });

    // Handle click on close button or outside modal
    var closeButtons = document.querySelectorAll('.close-modal, .modal .close, .modal-overlay');
    closeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('keyActionModal').style.display = 'none';
            if (document.querySelector('#keyActionModal input[type="password"]')) {
                document.querySelector('#keyActionModal input[type="password"]').value = '';
            }
            var alerts = document.querySelectorAll('#keyActionModal .alert');
            alerts.forEach(function (alert) {
                alert.parentNode.removeChild(alert);
            });

            var errorMessages = document.querySelectorAll('#keyActionModal .text-danger');
            errorMessages.forEach(function (error) {
                error.parentNode.removeChild(error);
            });
        });
    });
};


// Schedule Script
// Popup Open and Close Handling
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
    if (document.getElementById('opencreateModalSchedule')) {
        document.getElementById('opencreateModalSchedule').onclick = function() {
            createModalSchedule.style.display = "block";
        };
    }

    if (getComputedStyle(createModalSchedule).display === 'block' || createModalSchedule.classList.contains('open')) {
        const dateField = document.getElementById('date');
        const startField = document.getElementById('start_time');
        const endField = document.getElementById('end_time');
        const assignmentField = document.getElementById('assignment_id');
        const roomField = document.getElementById('room_id');
        const repeatField = document.getElementById('repeat');

        dateField.value = dateField.getAttribute('data-old-value');
        startField.value = startField.getAttribute('data-old-value');
        endField.value = endField.getAttribute('data-old-value');
        assignmentField.value = assignmentField.getAttribute('data-old-value');
        roomField.value = roomField.getAttribute('data-old-value');
        repeatField.value = repeatField.getAttribute('data-old-value');

    }

    const editForm = document.getElementById('editForm');
    if (getComputedStyle(editModalSchedule).display === 'block' || editModalSchedule.classList.contains('open')) {
        const dateField = document.getElementById('modalDate');
        const startField = document.getElementById('modalStart');
        const endField = document.getElementById('modalEnd');
        const assignmentField = document.getElementById('modalAssignment');
        const roomField = document.getElementById('modalRoom');
        const formAction = document.getElementById('modalScheduleAction');

        dateField.value = dateField.getAttribute('data-old-value');
        startField.value = startField.getAttribute('data-old-value');
        endField.value = endField.getAttribute('data-old-value');
        assignmentField.value = assignmentField.getAttribute('data-old-value');
        roomField.value = roomField.getAttribute('data-old-value');
        formAction.value = formAction.getAttribute('data-old-action');
        editForm.action = formAction.getAttribute('data-old-action');
    }

    // Open edit modal on edit button click
    document.querySelectorAll('.edit-button-schedule').forEach(function(button) {
        button.onclick = function() {
            var scheduleId = this.getAttribute('data-id');
            var date = this.getAttribute('data-date');
            var start = this.getAttribute('data-start');
            var end = this.getAttribute('data-end');
            var assignmentId = this.getAttribute('data-assignment-id');
            var roomId = this.getAttribute('data-room-id');

            document.getElementById('modalDate').value = date;
            document.getElementById('modalStart').value = start;
            document.getElementById('modalEnd').value = end;
            document.getElementById('modalAssignment').value = assignmentId;
            document.getElementById('modalRoom').value = roomId;
            document.getElementById('modalScheduleAction').value = 'schedule/' + scheduleId + '/edit';
            editForm.action = 'schedule/' + scheduleId + '/edit';

            editModalSchedule.style.display = "block";
        };
    });

    const editFormBatch = document.getElementById('editFormBatch');
    if (getComputedStyle(editModalScheduleBatch).display === 'block' || editModalScheduleBatch.classList.contains('open')) {
        // const dateField = document.getElementById('modalDateBatch');
        const startField = document.getElementById('modalStartBatch');
        const endField = document.getElementById('modalEndBatch');
        const assignmentField = document.getElementById('modalAssignmentBatch');
        const roomField = document.getElementById('modalRoomBatch');
        const formActionBatch = document.getElementById('modalScheduleActionBatch');

        // dateField.value = dateField.getAttribute('dataoldvalue');
        startField.value = startField.getAttribute('data-old-value');
        endField.value = endField.getAttribute('data-old-value');
        assignmentField.value = assignmentField.getAttribute('data-old-value');
        roomField.value = roomField.getAttribute('data-old-value');
        formActionBatch.value = formActionBatch.getAttribute('data-old-action');
        editFormBatch.action = formActionBatch.getAttribute('data-old-action');

        const datesContainer = document.getElementById('datesContainer');
        datesContainer.querySelectorAll('input[type="date"]').forEach(input => {
            input.value = input.getAttribute('data-old-value');
        });
    }

    // Open batch edit modal on edit button click
    document.querySelectorAll('.batch-edit-button-schedule').forEach(function(button) {
        button.onclick = function() {
            var scheduleIds = this.getAttribute('data-ids').split(',');
            var dates = this.getAttribute('data-dates').split(',');
            var start = this.getAttribute('data-start');
            var end = this.getAttribute('data-end');
            var assignmentId = this.getAttribute('data-assignment-id');
            var roomId = this.getAttribute('data-room-id');

            scheduleIds.forEach(function(id, index) {
            var inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'ids[]';
            inputId.value = id;
            idsContainer.appendChild(inputId);

            var inputDate = document.createElement('input');
            inputDate.type = 'date';
            inputDate.name = 'dates[]';
            inputDate.value = dates[index];
            inputDate.setAttribute('data-old-value', dates[index]);
            datesContainer.appendChild(inputDate);
        });

            document.getElementById('modalStartBatch').value = start;
            document.getElementById('modalEndBatch').value = end;
            document.getElementById('modalAssignmentBatch').value = assignmentId;
            document.getElementById('modalRoomBatch').value = roomId;
            document.getElementById('modalScheduleActionBatch').value = 'schedule/batch-edit?ids=' + scheduleIds.join(',');
            editFormBatch.action = 'schedule/batch-edit?ids=' + scheduleIds.join(',');

            editModalScheduleBatch.style.display = "block";
        };
    });

    // When the user clicks on <span> (x), close the create modal
    closecreateModalSchedule.onclick = function() {
        createModalSchedule.style.display = "none";
        var errorMessages = document.querySelectorAll('#createModalSchedule .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });

        document.getElementById('date').value = '';
        document.getElementById('start_time').value = '';
        document.getElementById('end_time').value = '';
        document.getElementById('assignment_id').value = '';
        document.getElementById('room_id').value = '';
        document.getElementById('repeat').value = '';
    };

    // When the user clicks on <span> (x), close the edit modal
    closeeditModalSchedule.onclick = function() {
        editModalSchedule.style.display = "none";
        var errorMessages = document.querySelectorAll('#editModalSchedule .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
    };

    // When the user clicks on <span> (x), close the batch edit modal
    closeeditModalScheduleBatch.onclick = function() {
        editModalScheduleBatch.style.display = "none";
        var errorMessages = document.querySelectorAll('#editModalScheduleBatch .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
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
                    this.innerHTML = 'Collapse <i class="fa-solid fa-caret-up" style="padding-left: 0px;"></i>';
                } else {
                    expandedContent.style.display = 'none';
                    this.innerHTML = 'Expand <i class="fa-solid fa-caret-down" style="padding-left: 5px;"></i>';
                }
            });
        });
    });
};

// Assignment Script
// Popup Open and Close Handling
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

    if (getComputedStyle(createModalAssignment).display === 'block' || createModalAssignment.classList.contains('open')) {
        const userField = document.getElementById('user_id');
        const subjectField = document.getElementById('subject_id');
        const classField = document.getElementById('kelas_id');

        if (userField) {
            userField.value = userField.getAttribute('data-old-value');
        }
        if (classField) {
            classField.value = classField.getAttribute('data-old-value');
        }
    }

    const editForm = document.getElementById('editForm');
    if (getComputedStyle(editModalAssignment).display === 'block' || editModalAssignment.classList.contains('open')) {
        const userField = document.getElementById('modalUser');
        const classField = document.getElementById('modalClass');
        const formAction = document.getElementById('modalAssignmentAction');

        userField.value = userField.getAttribute('data-old-value');
        classField.value = classField.getAttribute('data-old-value');
        formAction.value = formAction.getAttribute('data-old-action');
        editForm.action = formAction.getAttribute('data-old-action');
    }

    // Open edit modal on edit button click
    document.querySelectorAll('.edit-button-assignment').forEach(function(button) {
        button.onclick = function() {
            var assignmentId = this.getAttribute('data-id');
            var userId = this.getAttribute('data-user_id');
            var kelasId = this.getAttribute('data-kelas_id');

            document.getElementById('modalUser').value = userId;
            document.getElementById('modalClass').value = kelasId;
            document.getElementById('modalAssignmentAction').value = 'assignment/' + assignmentId + '/edit';
            editForm.action = 'assignment/' + assignmentId + '/edit';

            editModalAssignment.style.display = "block";
        };
    });

    // When the user clicks on <span> (x), close the create modal
    closecreateModalAssignment.onclick = function() {
        createModalAssignment.style.display = "none";
        var errorMessages = document.querySelectorAll('#createModalAssignment .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
        document.getElementById('user_id').value = '';
        document.getElementById('kelas_id').value = '';
    };

    // When the user clicks on <span> (x), close the edit modal
    closeeditModalAssignment.onclick = function() {
        editModalAssignment.style.display = "none";
        var errorMessages = document.querySelectorAll('#editModalAssignment .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
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
// Popup Open and Close Handling
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

    if (getComputedStyle(createModalSubject).display === 'block' || createModalSubject.classList.contains('open')) {
        const nameField = document.getElementById('name');

        nameField.value = nameField.getAttribute('data-old-value');
    }

    const editForm = document.getElementById('editForm');
    if (getComputedStyle(editModalSubject).display === 'block' || editModalSubject.classList.contains('open')) {
        const nameField = document.getElementById('modalSubjectName');
        const formAction = document.getElementById('modalSubjectAction');

        nameField.value = nameField.getAttribute('data-old-value');
        formAction.value = formAction.getAttribute('data-old-action');
        editForm.action = formAction.getAttribute('data-old-action');
    }

    // Open edit modal on edit button click
    document.querySelectorAll('.edit-button-subject').forEach(function(button) {
        button.onclick = function() {
            var subjectId = this.getAttribute('data-id');
            var subjectName = this.getAttribute('data-name');

            document.getElementById('modalSubjectId').value = subjectId;
            document.getElementById('modalSubjectName').value = subjectName;
            document.getElementById('modalSubjectAction').value = 'subject/' + subjectId + '/edit';
            editForm.action = 'subject/' + subjectId + '/edit';

            editModalSubject.style.display = "block";
        };
    });

    // When the user clicks on <span> (x), close the create modal
    closecreateModalSubject.onclick = function() {
        createModalSubject.style.display = "none";
        var errorMessages = document.querySelectorAll('#createModalSubject .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
        document.getElementById('name').value = '';
    };


    // When the user clicks on <span> (x), close the edit modal
    closeeditModalSubject.onclick = function() {
        editModalSubject.style.display = "none";
        var errorMessages = document.querySelectorAll('#editModalSubject .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
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
// Popup Open and Close Handling
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

    if (getComputedStyle(createModalRoom).display === 'block' || createModalRoom.classList.contains('open')) {
        const roomField = document.getElementById('room_number');

        if (roomField) {
            roomField.value = roomField.getAttribute('data-old-value');
        }
    }

    const editForm = document.getElementById('editForm');
    if (getComputedStyle(editModalRoom).display === 'block' || editModalRoom.classList.contains('open')) {
        const roomField = document.getElementById('modalRoomNumber');
        const formAction = document.getElementById('modalRoomAction');

        roomField.value = roomField.getAttribute('data-old-value');
        formAction.value = formAction.getAttribute('data-old-action');
        editForm.action = formAction.getAttribute('data-old-action');
    }

    // Open edit modal on edit button click
    document.querySelectorAll('.edit-button-room').forEach(function(button) {
        button.onclick = function() {
            var roomId = this.getAttribute('data-id');
            var roomNumber = this.getAttribute('data-room_number');

            document.getElementById('modalRoomId').value = roomId;
            document.getElementById('modalRoomNumber').value = roomNumber;
            document.getElementById('modalRoomAction').value = 'room/' + roomId + '/edit';
            editForm.action = 'room/' + roomId + '/edit';

            editModalRoom.style.display = "block";
        };
    });

    // When the user clicks on <span> (x), close the create modal
    closecreateModalRoom.onclick = function() {
        createModalRoom.style.display = "none";
        var errorMessages = document.querySelectorAll('#createModalRoom .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });

        document.getElementById('room_number').value = '';
    };

    // When the user clicks on <span> (x), close the edit modal
    closeeditModalRoom.onclick = function() {
        editModalRoom.style.display = "none";
        var errorMessages = document.querySelectorAll('#editModalRoom .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
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
// Popup Open and Close Handling
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

    if (getComputedStyle(createModalClass).display === 'block' || createModalClass.classList.contains('open')) {
        const prodiField = document.getElementById('prodi');
        const subjectField = document.getElementById('subject_id');
        const classField = document.getElementById('class');

        if (prodiField) {
            prodiField.value = prodiField.getAttribute('data-old-value');
        }
        if (subjectField) {
            subjectField.value = subjectField.getAttribute('data-old-value');
        }
        if (classField) {
            classField.value = classField.getAttribute('data-old-value');
        }
    }

    const editForm = document.getElementById('editForm');
    if (getComputedStyle(editModalClass).display === 'block' || editModalClass.classList.contains('open')) {
        const prodiField = document.getElementById('modalProdi');
        const subjectField = document.getElementById('modalSubject');
        const classField = document.getElementById('modalClass');
        const formAction = document.getElementById('modalClassAction');

        prodiField.value = prodiField.getAttribute('data-old-value');
        subjectField.value = subjectField.getAttribute('data-old-value');
        classField.value = classField.getAttribute('data-old-value');
        formAction.value = formAction.getAttribute('data-old-action');
        editForm.action = formAction.getAttribute('data-old-action');
    }

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
            document.getElementById('modalClassAction').value = 'class/' + classId + '/edit';
            editForm.action = 'class/' + classId + '/edit';

            editModalClass.style.display = "block";
        };
    });

    // When the user clicks on <span> (x), close the create modal
    closecreateModalClass.onclick = function() {
        createModalClass.style.display = "none";
        var errorMessages = document.querySelectorAll('#createModalClass .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });

        document.getElementById('prodi').value = '';
            document.getElementById('subject_id').value = '';
        document.getElementById('class').value = '';
    };

    // When the user clicks on <span> (x), close the edit modal
    closeeditModalClass.onclick = function() {
        editModalClass.style.display = "none";
        var errorMessages = document.querySelectorAll('#editModalClass .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
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
// Popup Open and Close Handling
if(document.getElementById('createModalUser') || document.getElementById('editModalUser')) {
    // Get the modal elements
    var createModalUser = document.getElementById("createModalUser");
    var editModalUser = document.getElementById("editModalUser");

    // Get the <span> elements that close the modals
    var closecreateModalUser = createModalUser.getElementsByClassName("close")[0];
    var closeeditModalUser = editModalUser.getElementsByClassName("close")[0];

    // Open create modal on button click
    if(document.getElementById('opencreateModalUser')){
        document.getElementById('opencreateModalUser').onclick = function() {
            createModalUser.style.display = "block";
        };
    }

    if (getComputedStyle(createModalUser).display === 'block' || createModalUser.classList.contains('open')) {
        const nameField = document.getElementById('nameCreate');
        const emailField = document.getElementById('emailCreate');
        const passwordField = document.getElementById('passwordCreate');

        nameField.value = nameField.getAttribute('data-old-value');
        emailField.value = emailField.getAttribute('data-old-value');
        passwordField.value = passwordField.getAttribute('data-old-value');

        if (document.querySelector('input[name="role"][data-old-value]:checked')) {
            const oldRole = document.querySelector('input[name="role"][data-old-value]:checked').getAttribute('data-old-value');
            if (oldRole === 'Admin') {
                document.getElementById('roleAdmin').checked = true;
            } else if (oldRole === 'Lecturer') {
                document.getElementById('roleLecturer').checked = true;
            } else if (oldRole === 'Assistant') {
                document.getElementById('roleAssistant').checked = true;
            }
        }
    }

    const editForm = document.getElementById('editForm');
    if (getComputedStyle(editModalUser).display === 'block' || editModalUser.classList.contains('open')) {
        const nameField = document.getElementById('modalUserName');
        const emailField = document.getElementById('modalUserEmail');
        const passwordField = document.getElementById('modalUserPassword');
        const formAction = document.getElementById('modalUserAction');

        nameField.value = nameField.getAttribute('data-old-value');
        emailField.value = emailField.getAttribute('data-old-value');
        passwordField.value = passwordField.getAttribute('data-old-value');
        formAction.value = formAction.getAttribute('data-old-action');
        editForm.action = formAction.getAttribute('data-old-action');
    }

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
            document.getElementById('modalUserAction').value = 'user/' + userId + '/edit';
            editForm.action = 'user/' + userId + '/edit';

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
        var errorMessages = document.querySelectorAll('#createModalUser .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });

        document.getElementById('nameCreate').value = '';
        document.getElementById('emailCreate').value = '';
        document.getElementById('passwordCreate').value = '';
        document.querySelectorAll('input[name="role"]').forEach(function (radio) {
            radio.checked = false;
        });
    };

    // When the user clicks on <span> (x), close the edit modal
    closeeditModalUser.onclick = function() {
        editModalUser.style.display = "none";
        var errorMessages = document.querySelectorAll('#editModalUser .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
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

// Login Script
// Popup Open and Close Handling
if (document.getElementById('loginModal')) {
    var loginModal = document.getElementById("loginModal");

    // Get the <span> elements that close the modals
    var closeloginModal = loginModal.getElementsByClassName("close")[0];

    // Open create modal on button click
    if (document.getElementById('openLoginModal')) {
        document.getElementById('openLoginModal').onclick = function() {
            loginModal.style.display = "block";
        };
    }

    if (getComputedStyle(loginModal).display === 'block' || loginModal.classList.contains('open')) {
        const emailField = document.getElementById('email');

        emailField.value = emailField.getAttribute('data-old-value');
    }

    // When the user clicks on <span> (x), close the create modal
    closeloginModal.onclick = function() {
        loginModal.style.display = "none";
        var errorMessages = document.querySelectorAll('#loginModal .text-danger');
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
    };

    // When the user clicks anywhere outside of the modals, close them
    window.onclick = function(event) {
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        }
    };
}

// Edit Profile Script
// Popup Open and Close Handling
if (document.getElementById('editProfileModal')) {
    var editProfileModal = document.getElementById("editProfileModal");

    // Get the <span> elements that close the modals
    var closeEditProfileModal = editProfileModal.getElementsByClassName("close")[0];

    // Open create modal on button click
    if (document.getElementById('openEditProfileModal')) {
        document.getElementById('openEditProfileModal').onclick = function() {
            editProfileModal.style.display = "block";
        };
    }

    const editForm = document.getElementById('editForm');
    if (getComputedStyle(editProfileModal).display === 'block' || editProfileModal.classList.contains('open')) {
        const nameField = document.getElementById('modalProfileName');
        const emailField = document.getElementById('modalProfileEmail');

        nameField.value = nameField.getAttribute('data-old-value');
        emailField.value = emailField.getAttribute('data-old-value');
    }

    // When the user clicks on <span> (x), close the create modal
    closeEditProfileModal.onclick = function() {
        editProfileModal.style.display = "none";
        var errorMessages = document.querySelectorAll('#editProfileModal .text-danger');
        errorMessages.forEach(function (error) {
            error.parentNode.removeChild(error);
        });
    };

    // When the user clicks anywhere outside of the modals, close them
    window.onclick = function(event) {
        if (event.target == editProfileModal) {
            editProfileModal.style.display = "none";
        }
    };
}
