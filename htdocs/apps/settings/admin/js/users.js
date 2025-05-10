function editUser(user) {
    document.getElementById("js-tmp").innerHTML = user;
    fetch("/apps/settings/admin/bin/user_info.php?id=" + user)
        .then(respone => respone.json())
        .then(data => {
            if (data.status == 500) {
                alert("Error to get user information: " + data.error);
            }else{
                document.getElementById("wg_title_username").innerHTML = data.name;
                document.getElementById("wg_username").value = data.name;
                document.getElementById("wg_mail").value = data.mail;
                if (data.admin == true) {
                    document.getElementById("wg_admin").setAttribute("selected", "");
                }else{
                    document.getElementById("wg_admin").removeAttribute("selected");    
                }
                document.getElementById("editWG").setAttribute("open", "");
            }
        })
        .catch(error => alert(error))
}

function closeEdit() {
    document.getElementById("editWG").removeAttribute("open");
}

function saveEdit() {
    var values = {
        id: document.getElementById("js-tmp").innerHTML,
        name: document.getElementById("wg_username").value,
        mail: document.getElementById("wg_mail").value,
        pw: document.getElementById("wg_pw").value,
        pw2: document.getElementById("wg_pw2").value
    }

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
            }else{
                alert("Error: " + data.error);
            }
        })
        .catch(error => alert(error))
}

function addUser() {
    document.getElementById("addWG").setAttribute("open", "");
}

function closeAdd() {
    document.getElementById("addWG").removeAttribute("open");
}