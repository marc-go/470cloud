console.log("Load Script options.js");

function edit(id) {
    var widget = document.getElementById("widget");
    if (widget) {
        widget.style.display = "block";
        widget.style.zIndex = "9999";
        widget.style.position = "fixed";
        widget.style.top = "50%";
        widget.style.left = "50%";
        widget.style.transform = "translate(-50%, -50%)";
        widget.style.backgroundColor = "#ffffff";
    } else {
        console.log("Element mit ID 'widget' nicht gefunden.");
    }
    document.getElementById("overlay").style.display = "block";
    var tmp = document.getElementById("js-tmp");
    if (tmp) {
        tmp.innerHTML = id;
    } else {
        console.log("Element mit ID 'js-tmp' nicht gefunden.");
    }
}

function closeWidget() {
    var widget = document.getElementById("widget");
    document.getElementById("overlay").style.display = "none";
    if (widget) {
        widget.style.display = "none";
    } else {
        console.log("Element mit ID 'widget' nicht gefunden.");
    }
}

function remove(file) {
    var name = document.getElementById("file").innerHTML + document.getElementById("js-tmp").innerHTML;
    fetch('bin/remove.php?name=' + name)
        .then(response => response.json())
        .then(data => {
            console.log(data.status);
            if (data.status === 200) {
                window.location.reload();
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.log(error));
}

function share(mode) {
    var id = document.getElementById("js-tmp").innerHTML;
    fetch('bin/share.php?id=' + id + '&mode=' + mode)
        .then(response => response.json())
        .then(data => {
            if (data.status === 200) {
                window.location.reload();
            } else {
                document.getElementById("error").innerHTML = "<br> Datei kann nicht geteilt werden.";
            }
        })
        .catch(error => console.log(error));
}

function rename(event) {
	event.preventDefault();
	
	var name = document.getElementById("name").value;
    var path = document.getElementById("file").innerHTML;
	var file = document.getElementById("js-tmp").innerHTML;
	
	console.log(document.getElementById("js-tmp").innerHTML);
	console.log(document.getElementById("file").innerHTML);
	console.log(document.getElementById("name").value);
	
    fetch('bin/rename.php?path=' + path + '&name=' + name + "&file=" + file)
        .then(response => response.json())
        .then(data => {
            if (data.status === 200) {
                window.location.reload();
            }else{
                alert(data.error);
            }
        })
        .catch(error => console.log(error));
}

function newFolder() {
	var widget2 = document.getElementById("widget2");
	widget2.style.display = "block";
    widget2.style.zIndex = "9999";
    widget2.style.position = "fixed";
    widget2.style.top = "50%";
    widget2.style.left = "50%";
    widget2.style.transform = "translate(-50%, -50%)";
    widget2.style.backgroundColor = "#ffffff";
	
	document.getElementById("overlay").style.display = "block";
}

function addFolder(event) {
	event.preventDefault();
	
	var path = document.getElementById("file").innerHTML;
	var name = document.getElementById("f-name").value;
	
	var folder = path + name;
	
	fetch("bin/folder.php?name=" + folder)
		.then(response => response.json())
		.then(data => {
			if (data.status == 200) {
				window.location.reload();
			}else{
				alert(data.error);
			}
		})
		.catch(error => alert(error))
}

function closeF() {
    document.getElementById("widget2").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}