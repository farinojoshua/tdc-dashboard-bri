import './bootstrap';
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";

document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("calendar");
    var calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin],
        events: "/api/deployments/events",
        eventDidMount: function (info) {
            info.el.classList.add("cursor-pointer");
            if (info.event.extendedProps.status_doc === "done") {
                info.el.style.backgroundColor = "#00ff00"; // Hijau
            } else if (info.event.extendedProps.status_doc === "in progress") {
                info.el.style.backgroundColor = "#ffcc00"; // Kuning
            } else if (info.event.extendedProps.status_doc === "not done") {
                info.el.style.backgroundColor = "#ff0000"; // Merah
            }
        },
        eventClick: function (info) {
            document.getElementById("modalTitle").textContent =
                info.event.title;

            let modalBody = `
                    <p>Module: ${info.event.extendedProps.module}</p>
                    <p>Server Type: ${info.event.extendedProps.server_type}</p>
                    <p>Status CM: ${info.event.extendedProps.status_cm}</p>
                    <p>Status Doc: ${info.event.extendedProps.status_doc}</p>
                    `;

            document.getElementById("modalBody").innerHTML = modalBody;

            // Menampilkan modal
            const modal = document.getElementById("eventInfoModal");
            modal.classList.remove("hidden");

            // Event untuk menutup modal
            document
                .getElementById("modalCloseButton")
                .addEventListener("click", function () {
                    modal.classList.add("hidden");
                });
        },
    });
    calendar.render();
});



