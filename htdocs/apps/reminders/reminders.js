"use strict";
console.log("Load /apps/reminders/reminders.js");

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
            document.getElementById("info").setAttribute("open", "");
        })
        .catch(error => console.log(error))
}

function closeInfo() {
    document.getElementById("info").removeAttribute("open");
}

function add() {
    document.getElementById("add").setAttribute("open", "");
}

function closeAdd() {
	document.getElementById("add").removeAttribute("open");
}

function deleteWidgetOpen(id) {
	document.getElementById("rmWidget").setAttribute("open", "");
	document.getElementById("js-tmp").innerHTML = id;
}

function deleteWidgetClose() {
	document.getElementById("rmWidget").removeAttribute("open");
}

function deleteRemind() {
	var id = document.getElementById("js-tmp").innerHTML;
	fetch("bin/delete.php?id=" + id)
		.then(response => response.json())
		.then(data => {
			if (data.status == 200) {
				window.location.reload();
			}else{
				alert("Error: More in the Console");
				console.log(data.error);
			}
		})
		.catch(error => alert("Error: " + error))
}

function restoreRemind() {
	var id = document.getElementById("js-tmp").innerHTML;
	fetch("bin/refresh.php?id=" + id)
		.then(response => response.json())
		.then(data => {
			if(data.status = 200) {
				window.location.reload();
			}else{
				alert("Error: " + data.error);
			}
		})
		.catch(error => alert("Error: " + error))
}