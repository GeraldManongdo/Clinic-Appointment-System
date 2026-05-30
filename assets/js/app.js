function setMessage(element, message, success = true) {
  element.textContent = message;
  element.className = success ? "text-success" : "text-danger";
}

let servicePage = 1;
const loadMoreServices = document.getElementById("loadMoreServices");
if (loadMoreServices) {
  loadMoreServices.addEventListener("click", () => {
    servicePage += 1;
    fetch(`${APP_URL}/?route=ajax&action=load_services&page=${servicePage}`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success && data.services.length) {
          const list = document.getElementById("serviceList");
          data.services.forEach((service) => {
            const html = `<div class="col-md-6 col-lg-4"><div class="card h-100 shadow-sm border-0"><img src="${service.image_path ? APP_URL + "/uploads/" + service.image_path : "https://images.unsplash.com/photo-1550831107-1553da8c8464?auto=format&fit=crop&w=900&q=80"}" class="card-img-top" alt="${service.title}"><div class="card-body"><h5>${service.title}</h5><p class="text-muted small">${service.description}</p></div></div></div>`;
            list.insertAdjacentHTML("beforeend", html);
          });
        }
      });
  });
}

function submitBooking() {
  const form = document.getElementById("bookingForm");
  const message = document.getElementById("bookingMessage");
  const data = new FormData(form);
  fetch(`${APP_URL}/?route=appointment/book`, { method: "POST", body: data })
    .then((res) => res.json())
    .then((data) => {
      setMessage(
        message,
        data.message ||
          (data.success ? "Booking confirmed." : "Booking failed."),
        data.success,
      );
      if (data.success) {
        form.reset();
      }
    });
}

const submitBookingButton = document.getElementById("submitBooking");
if (submitBookingButton) {
  submitBookingButton.addEventListener("click", submitBooking);
}

function cancelAppointment(id) {
  if (!confirm("Cancel this appointment?")) return;
  fetch(`${APP_URL}/?route=ajax`, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `action=cancel_appointment&id=${id}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        location.reload();
      }
    });
}

function openStatusModal(id) {
  document.getElementById("appointmentId").value = id;
  const modal = new bootstrap.Modal(document.getElementById("statusModal"));
  modal.show();
}

function updateAppointmentStatus() {
  const id = document.getElementById("appointmentId").value;
  const status = document.getElementById("appointmentStatus").value;
  const notes = document.getElementById("appointmentNote").value;
  fetch(`${APP_URL}/admin/index.php?route=ajax`, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `action=update_appointment_status&id=${id}&status=${encodeURIComponent(status)}&notes=${encodeURIComponent(notes)}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        location.reload();
      }
    });
}

function toggleService(id, visible) {
  fetch(`${APP_URL}/admin/index.php?route=ajax`, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `action=toggle_service&id=${id}&visible=${visible}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        location.reload();
      }
    });
}

function openServiceForm(id = 0) {
  document.getElementById("serviceForm").reset();
  document.getElementById("serviceId").value = id;
  document.getElementById("serviceVisible").checked = true;
  document.getElementById("serviceTitle").value = "";
  document.getElementById("serviceDescription").value = "";
  if (id) {
    const card = document
      .querySelector(`[onclick="openServiceForm(${id})"]`)
      .closest(".card");
    document.getElementById("serviceTitle").value = card
      .querySelector("h5")
      .textContent.trim();
    document.getElementById("serviceDescription").value = card
      .querySelector(".text-muted")
      .textContent.trim();
  }
}

function openAccountForm(id = 0) {
  document.getElementById("accountForm").reset();
  document.getElementById("accountId").value = id;
  document.getElementById("accountRole").value = "user";
  document.getElementById("accountName").value = "";
  document.getElementById("accountEmail").value = "";
  document.getElementById("accountPhone").value = "";
  if (id) {
    const row = document.querySelector(`[data-account-id="${id}"]`);
    if (row) {
      document.getElementById("accountName").value = row.dataset.name || "";
      document.getElementById("accountEmail").value = row.dataset.email || "";
      document.getElementById("accountPhone").value = row.dataset.phone || "";
      document.getElementById("accountRole").value = row.dataset.role || "user";
    }
  }
  const modal = new bootstrap.Modal(document.getElementById("accountModal"));
  modal.show();
}

function saveAccount() {
  const form = document.getElementById("accountForm");
  const data = new FormData(form);
  fetch(`${APP_URL}/admin/index.php?route=ajax`, { method: "POST", body: data })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        location.reload();
      }
    });
}

function saveService() {
  const form = document.getElementById("serviceForm");
  const data = new FormData(form);
  fetch(`${APP_URL}/admin/index.php?route=ajax`, { method: "POST", body: data })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        location.reload();
      }
    });
}

function saveSettings(formId) {
  const form = document.getElementById(formId);
  const data = new FormData(form);
  fetch(`${APP_URL}/admin/index.php?route=ajax`, { method: "POST", body: data })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        location.reload();
      }
    });
}

function addBlockDate() {
  const date = document.getElementById("blockDate").value;
  if (!date) return;
  fetch(`${APP_URL}/admin/index.php?route=ajax`, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `action=block_date&date=${encodeURIComponent(date)}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        location.reload();
      }
    });
}

const appointmentDate = document.getElementById("appointmentDate");
const appointmentTime = document.getElementById("appointmentTime");
const bookingDateStatus = document.getElementById("bookingDateStatus");
if (appointmentDate && appointmentTime) {
  appointmentDate.addEventListener("change", () => {
    const date = appointmentDate.value;
    bookingDateStatus.textContent = "";
    appointmentTime.innerHTML = "<option>Loading...</option>";
    fetch(`${APP_URL}/?route=ajax&action=blocked_slots&date=${date}`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          const blocked = data.slots || [];
          const slots = ["09:00", "10:00", "11:00", "13:00", "14:00", "15:00"];
          appointmentTime.innerHTML = '<option value="">Select a time</option>';
          let availableCount = 0;
          slots.forEach((slot) => {
            const disabled = blocked.includes(slot) ? "disabled" : "";
            if (!disabled) {
              availableCount += 1;
            }
            appointmentTime.innerHTML += `<option value="${slot}" ${disabled}>${slot}${disabled ? " — unavailable" : ""}</option>`;
          });
          if (availableCount === 0) {
            bookingDateStatus.textContent =
              "This date has no available slots. Please choose another date.";
            bookingDateStatus.className = "text-danger small";
          } else {
            bookingDateStatus.textContent = `${availableCount} available slot(s) for this date.`;
            bookingDateStatus.className = "text-success small";
          }
        }
      });
  });
}
