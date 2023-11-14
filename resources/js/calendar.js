import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";

// Store module colors for events
let moduleColors = {};

// Function to generate a random color
function generateRandomColor() {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

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
            // set cursor to pointer on hover
            info.el.style.cursor = "pointer";
            info.el.style.maxWidth = "100%";
            // Set the event color based on its module
            const { module } = info.event.extendedProps;
            const moduleColorsMap = {
                FAM: "#0D1282",
                EIM: "#713ABE",
                BGP: "#3085C3",
                Consol: "#088395",
                "S/4GL": "#FF6969",
                PaPM: "#4D3C77",
                FPSL: "#9F0D7F",
                Other: "#AED8CC",
            };

            moduleColors[module] =
                moduleColorsMap[module] || generateRandomColor();
            updateLegend();
            info.el.style.backgroundColor = moduleColors[module];
        },
        eventClick: function (info) {
            // Display a modal with event details on click
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

            // Close the modal functionality
            document
                .getElementById("modalCloseButton")
                .addEventListener("click", function () {
                    modal.classList.add("hidden");
                    modal.classList.remove("flex");
                });
        },
        dayCellDidMount: function (info) {
            // Highlight weekends with a specific color
            const dayIndex = new Date(info.date).getDay();
            if (dayIndex === 0 || dayIndex === 6) {
                info.el.style.backgroundColor = "rgba(255, 0, 0, 0.2)";
            }
        },
    });

    // Handle navigation based on the filter form input
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
