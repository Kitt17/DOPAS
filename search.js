// Function to open and close sidebar
function toggleMenu() {

    // Get sidebar element
    const sidebar = document.getElementById("sidebar");

    // Get main content area
    const content = document.getElementById("main-content");

    // Toggle sidebar visibility
    sidebar.classList.toggle("active");

    // Shift content when sidebar opens
    content.classList.toggle("shifted");
}
function closeSidebar() {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("main-content");

    sidebar.classList.remove("active");
    content.classList.remove("shifted");
}
