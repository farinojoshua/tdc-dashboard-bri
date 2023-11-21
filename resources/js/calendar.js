import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";

// Array warna yang telah ditentukan sebelumnya
const predefinedColors = [
    "#435585",
    "#CC9500",
    "#3F0071",
    "#005561",
    "#571622",
    "#007D57",
    "#000000",
    "#747687",
    "#6D3500",
    "#404040",
    "#A26953",
];

// Fungsi untuk mendapatkan warna berdasarkan ID modul
function getColorForModule(moduleId) {
    const index = Math.abs(moduleId.hashCode()) % predefinedColors.length;
    return predefinedColors[index];
}

// Fungsi bantuan untuk mengubah string menjadi hash code
String.prototype.hashCode = function () {
    let hash = 0,
        i,
        chr;
    for (i = 0; i < this.length; i++) {
        chr = this.charCodeAt(i);
        hash = (hash << 5) - hash + chr;
        hash |= 0; // Ubah menjadi integer 32bit
    }
    return hash;
};

// Store module colors for events
let moduleColors = {};

// Update the legend showing the colors associated with different modules
function updateLegend() {
    const legendDiv = document.getElementById("calendarLegend");
    legendDiv.innerHTML = "";
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
    const calendarEl = document.getElementById("calendar");

    // Initialize and render the full calendar
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin],
        events: "/api/deployments/events",
        eventContent: function (arg) {
            const truncatedTitle = arg.event.title.substring(0, 20);
            const title = document.createElement("div");
            title.innerHTML =
                `<strong>${truncatedTitle}</strong>` +
                (arg.event.title.length > 20 ? "..." : "");
            title.style.fontSize = "1.2em";
            title.style.whiteSpace = "nowrap";
            title.style.overflow = "hidden";
            title.style.textOverflow = "ellipsis";

            const module = document.createElement("div");
            module.innerHTML = `Module: ${arg.event.extendedProps.module}`;
            module.style.marginTop = "8px";
            module.style.whiteSpace = "nowrap";
            module.style.overflow = "hidden";
            module.style.textOverflow = "ellipsis";

            const serverType = document.createElement("div");
            serverType.innerHTML = `Server: ${arg.event.extendedProps.server_type}`;
            serverType.style.marginTop = "2px";
            serverType.style.whiteSpace = "nowrap";
            serverType.style.overflow = "hidden";
            serverType.style.textOverflow = "ellipsis";

            return { domNodes: [title, module, serverType] };
        },
        eventDidMount: function (info) {
            info.el.style.cursor = "pointer";
            info.el.style.maxWidth = "100%";
            const module = info.event.extendedProps.module;
            const color = getColorForModule(module);
            moduleColors[module] = color;
            updateLegend();
            info.el.style.backgroundColor = color;
        },
        eventClick: function (info) {
            const modalTitle = document.getElementById("modalTitle");
            const modalBody = document.getElementById("modalBody");
            const modal = document.getElementById("eventInfoModal");

            modalTitle.textContent = info.event.title;
            const formattedDate = new Date(info.event.start).toLocaleDateString(
                "id-ID",
                { year: "numeric", month: "long", day: "numeric" }
            );
            modalBody.innerHTML = `
                <p><strong>Deploy:</strong> ${formattedDate}</p>
                <p><strong>Module:</strong> ${info.event.extendedProps.module}</p>
                <p><strong>Server :</strong> ${info.event.extendedProps.server_type}</p>
                <p><strong>Status Doc:</strong> ${info.event.extendedProps.status_doc}</p>
                <p><strong>Deskripsi Dokumen:</strong> ${info.event.extendedProps.document_description}</p>
                <p><strong>Status CM:</strong> ${info.event.extendedProps.status_cm}</p>
                <p><strong>Deskripsi CM:</strong> ${info.event.extendedProps.cm_description}</p>
            `;
            modal.classList.remove("hidden");
            modal.classList.add("flex");

            document
                .getElementById("modalCloseButton")
                .addEventListener("click", function () {
                    modal.classList.add("hidden");
                    modal.classList.remove("flex");
                });
        },
        dayCellDidMount: function (info) {
            const dayIndex = new Date(info.date).getDay();
            if (dayIndex === 0 || dayIndex === 6) {
                info.el.style.backgroundColor = "rgba(255, 0, 0, 0.2)";
            }
        },
    });

    document
        .getElementById("calendarFilterForm")
        .addEventListener("submit", function (e) {
            e.preventDefault();
            const month = parseInt(document.getElementById("month").value, 10);
            const year = parseInt(document.getElementById("year").value, 10);
            const isoDate = `${year}-${String(month + 1).padStart(2, "0")}-01`;
            calendar.gotoDate(isoDate);
        });

    calendar.render();
});
