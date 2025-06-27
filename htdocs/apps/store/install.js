function info(id) {
    document.getElementById(id).setAttribute("open", "");
}

function closeWG(id) {
    document.getElementById(id).removeAttribute("open");
}

function install(id) {
    window.location.href = "/apps/install.php?id=" + id;
}