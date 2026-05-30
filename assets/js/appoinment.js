let state = {
    service_id: null,
    serviceName: null,
    servicePrice: null,
    serviceDoctor: null,
    selectedDate: null,
    selectedTime: null
};

// SERVICE SELECT
document.querySelectorAll('.service-opt').forEach(el => {
    el.addEventListener('click', () => {

        document.querySelectorAll('.service-opt').forEach(x => x.classList.remove('selected'));
        el.classList.add('selected');

        state.service_id = el.dataset.service;
        state.serviceName = el.dataset.name;
        state.servicePrice = el.dataset.price;
        state.serviceDoctor = el.dataset.doctor;

        updateSummary();
    });
});

// SUMMARY
function updateSummary() {
    document.getElementById('sumService').innerText = state.serviceName || '—';
    document.getElementById('sumDoctor').innerText = state.serviceDoctor || '—';
    document.getElementById('sumPrice').innerText = state.servicePrice ? '₱' + state.servicePrice : '—';

    checkReady();
}

// READY CHECK
function checkReady() {
    const ready =
        state.service_id &&
        state.selectedDate &&
        state.selectedTime &&
        document.getElementById('firstName').value &&
        document.getElementById('email').value &&
        document.getElementById('agree').checked;

    document.getElementById('confirmBtn').disabled = !ready;
}

// FORM INPUT LISTENERS
['firstName','email','phone','agree'].forEach(id => {
    document.getElementById(id).addEventListener('input', checkReady);
    document.getElementById(id).addEventListener('change', checkReady);
});

// SUBMIT (PHP BACKEND HOOK)
document.getElementById('confirmBtn').addEventListener('click', () => {

    fetch("<?= APP_URL ?>/?route=appointment", {
        method: "POST",
        body: JSON.stringify(state),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message || "Booked successfully!");
        location.reload();
    });

});