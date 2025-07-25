document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("home").addEventListener("click", function() {
        window.location.href = "/apps/home";
    });

    document.getElementById("files").addEventListener("click", function() {
        window.location.href = "/apps/files";
    });

    document.getElementById("reminders").addEventListener("click", function() {
        window.location.href = "/apps/reminders";
    });

    document.getElementById("settings").addEventListener("click", function() {
        window.location.href = "/apps/settings";
    });

    document.getElementById("store").addEventListener("click", function() {
        window.location.href = "/apps/store";
    });

    document.getElementById("all").addEventListener("click", function() {
        window.location.href = "/apps/all.php";
    });
});