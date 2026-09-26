// Client-side validation

// Check email format
function isValidEmail(email) {
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
}

// Validate register form
function validateRegister(form) {
    var name = form.elements['name'].value.trim();
    var email = form.elements['email'].value.trim();
    var password = form.elements['password'].value;

    if (name === "" || email === "" || password === "") {
        alert("Please fill in all required fields.");
        return false;
    }
    if (!isValidEmail(email)) {
        alert("Invalid email format.");
        return false;
    }
    return true;
}

// Validate login form
function validateLogin(form) {
    var email = form.elements['email'].value.trim();
    var password = form.elements['password'].value;

    if (email === "" || password === "") {
        alert("Please fill in all required fields.");
        return false;
    }
    if (!isValidEmail(email)) {
        alert("Invalid email format.");
        return false;
    }
    return true;
}

// Validate appointment form (add / edit)
function validateAppointment(form) {
    var staff = form.elements['staff_id'].value;
    var date = form.elements['appointment_date'].value;
    var time = form.elements['appointment_time'].value;
    var purpose = form.elements['purpose'].value.trim();

    if (staff === "" || date === "" || time === "" || purpose === "") {
        alert("Please fill in all required fields.");
        return false;
    }
    return true;
}

// AJAX: check appointment slot availability (no page reload)
function checkSlot() {
    var staff = document.getElementById('staff_id').value;
    var date = document.getElementById('appointment_date').value;
    var time = document.getElementById('appointment_time').value;
    var statusDiv = document.getElementById('slot_status');

    // Only check when all three are selected
    if (staff === "" || date === "" || time === "") {
        statusDiv.innerHTML = "";
        return;
    }

    var base = (typeof BASE_URL !== 'undefined') ? BASE_URL : '/';
    var url = base + 'ajax/check_appointment.php?staff_id=' + encodeURIComponent(staff) +
              '&date=' + encodeURIComponent(date) +
              '&time=' + encodeURIComponent(time);

    fetch(url)
        .then(function (response) { return response.text(); })
        .then(function (text) {
            if (text.indexOf("already booked") !== -1) {
                statusDiv.innerHTML = '<div class="alert alert-danger py-1 mb-0">' + text + '</div>';
            } else if (text.indexOf("available") !== -1) {
                statusDiv.innerHTML = '<div class="alert alert-success py-1 mb-0">' + text + '</div>';
            } else {
                statusDiv.innerHTML = '<div class="alert alert-warning py-1 mb-0">' + text + '</div>';
            }
        });
}
