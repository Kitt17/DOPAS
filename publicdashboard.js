
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


