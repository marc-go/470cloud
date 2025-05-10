function saveSettings() {
    var values = {
        id: document.getElementById("js-tmp").innerHTML,
        name: document.getElementById("user").value,
        mail: document.getElementById("mail").value,
        pw: document.getElementById("password").value,
        pw2: document.getElementById("password2").value
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
            }else{
                alert("Error: " + data.error);
            }
        })
        .catch(error => alert(error))
}