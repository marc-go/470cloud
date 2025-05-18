window.editUser = function(user) {
    document.getElementById("js-tmp").innerHTML = user;
    fetch("/apps/settings/admin/bin/user_info.php?id=" + user)
        .then(response => response.json())
        .then(data => {
            if (data.status == 500) {
                alert("Error to get user information: " + data.error);
            } else {
                document.getElementById("wg_title_username").innerHTML = data.name;
                document.getElementById("wg_username").value = data.name;
                document.getElementById("wg_mail").value = data.mail;
                if (data.admin === true) {
                    document.getElementById("wg_admin").setAttribute("selected", "");
                } else {
                    document.getElementById("wg_admin").removeAttribute("selected");
                }
                document.getElementById("editWG").setAttribute("open", "");
            }
        })
        .catch(error => alert(error));
};

window.closeEdit = function() {
    document.getElementById("editWG").removeAttribute("open");
};

window.saveEdit = function() {
    var values = {
        id: document.getElementById("js-tmp").innerHTML,
        name: document.getElementById("wg_username").value,
        mail: document.getElementById("wg_mail").value,
        pw: document.getElementById("wg_pw").value,
        pw2: document.getElementById("wg_pw2").value,
        admin: document.getElementById("wg_admin").selected
    };

    fetch("/apps/settings/admin/bin/user_edit.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(values)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status == 200) {
                window.location.reload();
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => alert(error));
};

window.addUserOpen = function() {
    document.getElementById("addWG").setAttribute("open", "");
};

window.closeAdd = function() {
    document.getElementById("addWG").removeAttribute("open");
};

window.addUser = function() {
    var values = {
        user: document.getElementById("user").value,
        mail: document.getElementById("mail").value,
        pw: document.getElementById("pw").value,
        pw2: document.getElementById("pw2").value,
        admin: document.getElementById("admin").selected
    };

    fetch("/apps/settings/admin/bin/adduser.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(values)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status == 200) {
                window.location.reload();
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => alert(error));
};

window.removeUser() = function() {
    confirm("Do you want that the user deleted? All user datas will be removed.");


}