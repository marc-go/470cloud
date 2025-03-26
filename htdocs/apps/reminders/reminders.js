function remove(id) {
	fetch('bin/remove.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.status == 500) {
                alert("Es gab einen Fehler beim Verarbeiten der Anfrage.");
            } else {
				window.location.reload();
				/*
                var objects = document.getElementsByClassName(id);
				objects.forEach(function(element) {
					document.getElementById("js-name").innerHTML = element.getAttribute("name");
					if (element.getAttribute("type") == "today") {
						var tmp = document.getElementById("js-tmp-1");
						tmp.innerHTML = tmp.innerHTML - 1;
					}
					element.remove();
				});
				var tmp1 = document.getElementById("js-tmp-1");
				var tmp2 = document.getElementById("js-tmp-2");
				
				if (tmp1.innerHTML == 0) {
					var object = document.createElement("span");
					object.textContent = "Für heute alles erledigt.";
					document.getElementById("today").appendChild(object);
				}
				
				if (tmp2.innerHTML == 0) {
					var object = document.createElement("span");
					object.textContent = "Hier gibt es nichts.";
					document.getElementById("all").appendChild(object);
				}
					
				var object2 = document.createElement("span");
				object2.textContent = document.getElementById("js-name").innerHTML;
				document.getElementById("removed").appendChild(object);*/
            }
        })
        .catch(error => console.log(error))
}

function info(id) {
    fetch('bin/info.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById("w-name").innerHTML = data.name;
            document.getElementById("w-date").innerHTML = data.date;
            document.getElementById("w-temp").innerHTML = data.id;
            document.getElementById("info").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        })
        .catch(error => console.log(error))
}

function closeInfo() {
    document.getElementById("info").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}

function add() {
    document.getElementById("add").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function closeAdd() {
    document.getElementById("add").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}

function submitAdd() {
    var name = document.getElementById("a-name").value;
    var date = document.getElementById("a-date").value;

    if (!name || !date) {
        alert("Bitte füllen Sie alle Felder aus.");
        return;
    }

    var data = {
        name: name,
        date: date
    };

    fetch("bin/add.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status == 200) {
            window.location.reload();
        } else {
            alert("Fehler: " + data.error);
        }
    })
    .catch(error => alert(error));
}

function deleteWidgetOpen(id) {
	document.getElementById("overlay").style.display = "block";
	document.getElementById("delete").style.display = "block";
	document.getElementById("js-tmp").innerHTML = id;
}

function deleteWidgetClose() {
	document.getElementById("overlay").style.display = "none";
	document.getElementById("delete").style.display = "none";
}

function refreshRemind() {
	var id = document.getElementById("js-tmp").innerHTML;
	fetch("bin/refresh.php?id=" + id)
		.then(response => response.json())
		.then(data => {
			if (data.status == 200) {
				window.location.reload();
			}else{
				alert("Error: " + data.error);
			}
		})
		.catch(error => alert("Error: " + data.error))
}

function deleteRemind() {
	var id = document.getElementById("js-tmp").innerHTML;
	fetch("bin/delete.php?id=" + id)
		.then(response => response.json())
		.then(data => {
			if (data.status == 200) {
				window.location.reload();
			}else{
				alert("Error: " + data.error);
			}
		})
		.catch(error => alert("Error: " + data.error))
}