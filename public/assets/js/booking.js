/**
 * Booking Form Functionality
 * Handles form validation and submission
 */

document.addEventListener("DOMContentLoaded", function () {
  initializeBookingForm();
});

function initializeBookingForm() {
  const form = document.getElementById("bookingForm");
  if (form) {
    form.addEventListener("submit", handleBookingSubmit);
    setupFormValidation();
  }
}

// Handle form submission
function handleBookingSubmit(e) {
  e.preventDefault();

  if (!validateBookingForm()) {
    showToast("Please fill in all required fields correctly", "danger");
    return;
  }

  // Collect form data
  const formData = new FormData(this);
  const data = Object.fromEntries(formData);

  // Show loading state
  const submitBtn = this.querySelector('button[type="submit"]');
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = "⏳ Processing...";
  submitBtn.disabled = true;

  // Submit form
  fetch("./api/booking_api.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        showToast("Appointment booked successfully!", "success");
        // Redirect to confirmation page
        setTimeout(() => {
          window.location.href =
            "booking-confirmation.php?id=" + result.appointment_id;
        }, 1500);
      } else {
        showToast(result.message || "Error booking appointment", "danger");
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showToast("Connection error. Please try again.", "danger");
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    });
}

// Validate booking form
function validateBookingForm() {
  const form = document.getElementById("bookingForm");
  if (!form) return false;

  let isValid = true;
  const inputs = form.querySelectorAll(
    "input[required], select[required], textarea[required]",
  );

  inputs.forEach((input) => {
    if (!input.value.trim()) {
      markFieldInvalid(input);
      isValid = false;
    } else if (input.type === "email" && !validateEmail(input.value)) {
      markFieldInvalid(input);
      isValid = false;
    } else if (input.type === "tel" && !validatePhone(input.value)) {
      markFieldInvalid(input);
      isValid = false;
    } else if (input.type === "date") {
      const selectedDate = new Date(input.value);
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      if (selectedDate < today) {
        markFieldInvalid(input);
        isValid = false;
      } else {
        markFieldValid(input);
      }
    } else {
      markFieldValid(input);
    }
  });

  return isValid;
}

// Mark field as invalid
function markFieldInvalid(input) {
  input.classList.remove("is-valid");
  input.classList.add("is-invalid");
}

// Mark field as valid
function markFieldValid(input) {
  input.classList.remove("is-invalid");
  input.classList.add("is-valid");
}

// Setup real-time validation
function setupFormValidation() {
  const form = document.getElementById("bookingForm");
  if (!form) return;

  const inputs = form.querySelectorAll("input, select, textarea");
  inputs.forEach((input) => {
    input.addEventListener("blur", function () {
      validateField(this);
    });

    input.addEventListener("input", function () {
      if (this.classList.contains("is-invalid")) {
        validateField(this);
      }
    });
  });
}

// Validate individual field
function validateField(field) {
  let isValid = true;

  if (field.hasAttribute("required") && !field.value.trim()) {
    isValid = false;
  } else if (
    field.type === "email" &&
    field.value &&
    !validateEmail(field.value)
  ) {
    isValid = false;
  } else if (
    field.type === "tel" &&
    field.value &&
    !validatePhone(field.value)
  ) {
    isValid = false;
  } else if (field.type === "date" && field.value) {
    const selectedDate = new Date(field.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    if (selectedDate < today) {
      isValid = false;
    }
  }

  if (isValid) {
    markFieldValid(field);
  } else {
    markFieldInvalid(field);
  }
}

// Auto-update available times based on selected date
function updateAvailableTimes(dateString) {
  // This would typically fetch available times from the server
  console.log("Updating times for date:", dateString);
}

// Export functions
window.clinicBooking = {
  initializeBookingForm,
  handleBookingSubmit,
  validateBookingForm,
  validateField,
};
