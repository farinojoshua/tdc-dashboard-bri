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
         eventContent: function (arg) {
             let arrayOfDomNodes = [];

             let truncatedTitle = arg.event.title.substring(0, 20);

             let title = document.createElement("div");
             title.innerHTML = `<strong>${truncatedTitle}</strong>`;
             if (arg.event.title.length > 20) {
                 title.innerHTML += "..."; // Tambahkan tanda ellipsis jika judul dipotong
             }

             title.style.fontSize = "1.2em"; // Membesarkan teks
             title.style.whiteSpace = "normal"; // Membuat teks berjajar rata
             title.style.overflowWrap = "break-word"; // Memaksa teks untuk pindah baris
             arrayOfDomNodes.push(title);

             let module = document.createElement("div");
             module.innerHTML = `Module: ${arg.event.extendedProps.module}`;
             module.style.marginTop = "8px"; // Tambahkan margin top 2px
             arrayOfDomNodes.push(module);

             let serverType = document.createElement("div");
             serverType.innerHTML = `Server: ${arg.event.extendedProps.server_type}`;
             serverType.style.marginTop = "2px"; // Tambahkan margin top 2px
             arrayOfDomNodes.push(serverType);

             return { domNodes: arrayOfDomNodes };
         },
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
         dayCellDidMount: function (info) {
             // Mengambil index hari (0 = Minggu, 1 = Senin, ..., 6 = Sabtu)
             const dayIndex = new Date(info.date).getDay();

             // Jika hari adalah Sabtu atau Minggu, ubah warna latar belakangnya menjadi merah
             if (dayIndex === 0 || dayIndex === 6) {
                 info.el.style.backgroundColor = "rgba(255, 0, 0, 0.2)"; // Anda bisa mengganti warna merah sesuai keinginan
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
             console.log(isoDate);

             // Navigate to the specific month and year
             calendar.gotoDate(isoDate);
         });
    calendar.render();
});
