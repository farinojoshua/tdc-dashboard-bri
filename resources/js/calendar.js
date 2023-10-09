import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";

let moduleColors = {}; // save the module colors in this object

// generate random color if the module is not fam or EIM or BGP or Consol or S/4GL or PaPM or FPSL or Other
function generateRandomColor() {
    const letters = "0123456789ABCDEF"; // Hexadecimal letters
    let color = "#";

    // Generate a random hex color
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// Update the legend based on the module colors
function updateLegend() {
    const legendDiv = document.getElementById("calendarLegend");
    legendDiv.innerHTML = ""; // Clear the legend
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

// When the DOM is ready, render the calendar
document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("calendar");
    var calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin],
        events: "/api/deployments/events", // URL to fetch events
        eventContent: function (arg) {
            let arrayOfDomNodes = []; // create an empty array to store the DOM nodes
            let truncatedTitle = arg.event.title.substring(0, 20); // cut the title to 20 chars
            let title = document.createElement("div");
            title.innerHTML = `<strong>${truncatedTitle}</strong>`;
            if (arg.event.title.length > 20) {
                title.innerHTML += "..."; // add ... if the title is truncated
            }

            title.style.fontSize = "1.2em";
            title.style.whiteSpace = "normal";
            title.style.overflowWrap = "break-word";
            arrayOfDomNodes.push(title); // put the title in the array

            let module = document.createElement("div");
            module.innerHTML = `Module: ${arg.event.extendedProps.module}`;
            module.style.marginTop = "8px";
            arrayOfDomNodes.push(module);

            let serverType = document.createElement("div");
            serverType.innerHTML = `Server: ${arg.event.extendedProps.server_type}`;
            serverType.style.marginTop = "2px";
            arrayOfDomNodes.push(serverType);

            return { domNodes: arrayOfDomNodes };
        },
        eventDidMount: function(info) {
            info.el.classList.add("cursor-pointer");

            const { module } = info.event.extendedProps; // get the module name

            const moduleColorsMap = {
                "FAM": "#0D1282",
                "EIM": "#713ABE",
                "BGP": "#3085C3",
                "Consol": "#088395",
                "S/4GL": "#FF6969",
                "PaPM": "#4D3C77",
                "FPSL": "#9F0D7F",
                "Other": "#AED8CC"
            };

            // If the module color doesn't exist, generate a random one
            if (!moduleColors[module]) {
                moduleColors[module] = moduleColorsMap[module] || generateRandomColor();
            }

            updateLegend();
            info.el.style.backgroundColor = moduleColors[module];
        },
        eventClick: function (info) {
            document.getElementById("modalTitle").textContent =
                info.event.title;


            let startDate = new Date(info.event.start);
            let options = { year: "numeric", month: "long", day: "numeric" };
            let formattedDate = startDate.toLocaleDateString("id-ID", options);

            let modalBody = `
                    <p><strong>Deploy:</strong> ${formattedDate}</p>
                    <p><strong>Module:</strong> ${info.event.extendedProps.module}</p>
                    <p><strong>Server :</strong> ${info.event.extendedProps.server_type}</p>
                    <p><strong>Status Doc:</strong> ${info.event.extendedProps.status_doc}</p>
                    <p><strong>Deskripsi Dokumen:</strong> ${info.event.extendedProps.document_description}</p>
                    <p><strong>Status CM:</strong> ${info.event.extendedProps.status_cm}</p>
                    <p><strong>Deskripsi CM:</strong> ${info.event.extendedProps.cm_description}</p>
                    `;

            document.getElementById("modalBody").innerHTML = modalBody;

            // show modal
            const modal = document.getElementById("eventInfoModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");

            // close modal
            document
                .getElementById("modalCloseButton")
                .addEventListener("click", function () {
                    modal.classList.add("hidden");
                    modal.classList.remove("flex");
                });
        },
        dayCellDidMount: function (info) {
            const dayIndex = new Date(info.date).getDay(); // Get the day index (0-6)

            // If the day is Saturday or Sunday, add the highlight class
            if (dayIndex === 0 || dayIndex === 6) {
                info.el.style.backgroundColor = "rgba(255, 0, 0, 0.2)"; // Set the background color
            }
        },
     });

    document
        .getElementById("calendarFilterForm")
        .addEventListener("submit", function (e) {
            e.preventDefault(); // Prevent the default form submission

            const month = parseInt(document.getElementById("month").value, 10);
            const year = parseInt(document.getElementById("year").value, 10);

            // Format the date in ISO8601
            const isoDate = `${year}-${String(month + 1).padStart(2, "0")}-01`;

            // Navigate to the specific month and year
            calendar.gotoDate(isoDate);
        });
        calendar.render(); // Render the calendar
    });
