import './bootstrap';
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";

let moduleColors = {};

function generateRandomColor() {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function updateLegend() {
    const legendDiv = document.getElementById("calendarLegend");
    legendDiv.innerHTML = ""; // Kosongkan legenda lama
    for (const [module, color] of Object.entries(moduleColors)) {
        const colorBox = document.createElement("div");
        colorBox.style.backgroundColor = color;
        colorBox.style.width = "20px";
        colorBox.style.height = "20px";
        colorBox.style.display = "inline-block";

        const text = document.createElement("span");
        text.innerHTML = ` : ${module} &nbsp;`;

        legendDiv.appendChild(colorBox);
        legendDiv.appendChild(text);
    }
}


document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("calendar");
    var calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin],
        events: "/api/deployments/events",
        eventDidMount: function (info) {
            info.el.classList.add("cursor-pointer");
            const module = info.event.extendedProps.module;
            if (!moduleColors[module]) {
                moduleColors[module] = generateRandomColor();
                updateLegend();
            }
            info.el.style.backgroundColor = moduleColors[module];
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



