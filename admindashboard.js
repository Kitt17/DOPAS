// Function to open and close sidebar
function toggleMenu() {

    const sidebar = document.getElementById("sidebar");

    const content = document.getElementById("main-content");

    sidebar.classList.toggle("active");

    content.classList.toggle("shifted");
}

function closeSidebar() {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("main-content");

    sidebar.classList.remove("active");
    content.classList.remove("shifted");
}

// Display notifications dynamically
const notificationList = document.getElementById("notification-list");
notifications.forEach(notif => {
    const li = document.createElement("li");
    li.textContent = `${notif.from} reported from ${notif.location} - ${notif.time}`;
    notificationList.appendChild(li);
});

// Update notification count
document.getElementById("notification-count").textContent = notifications.length;


