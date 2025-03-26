function install(id) {
	document.getElementById("widget").style.display = "block";
	document.getElementById("overlay").style.display = "block";
	document.getElementById("js-tmp").innerHTML = id;
}

function closeInstallConfirm() {
	document.getElementById("widget").style.display = "none";
	document.getElementById("overlay").style.display = "none";
}
	
function submitInstall() {
	const id = document.getElementById("js-tmp").innerHTML;
	
	document.getElementById("widget-content").style.display = "none";
	document.getElementById("widget-loader").style.display = "block";
	
	fetch("../installer.php?id=" + id)
		.then(response => response.json())
		.then(data => {
			document.getElementById("widget-loader").style.display = "none";
			if (data.status == 200) {
				document.getElementById("widget-success").style.display = "block";
			}else{
				document.getElementById("widget-error").style.display = "block";
				document.getElementById("error").innerHTML = data.error;
			}
		})
		.catch(error => {
			document.getElementById("widget-loader").style.display = "none";
			document.getElementById("widget-error").style.display = "block";
			document.getElementById("error").innerHTML = error;
		})
}

function remove(id) {
	document.getElementById("widget-remove-app").style.display = "block";
	document.getElementById("overlay").style.display = "block";
	document.getElementById("js-tmp").innerHTML = id;
}

function submitRemoveApp() {
	const id = document.getElementById("js-tmp").innerHTML;
	document.getElementById("widget-remove-app-content").style.display = "none";
	document.getElementById("widget-remove-app-loader").style.display = "block";
	
	fetch("../remove.php?id=" + id)
		.then(response => response.json())
		.then(data => {
			if (data.status == 200) {
				document.getElementById("widget-remove-app-loader").style.display = "none";
				document.getElementById("widget-remove-app-success").style.display = "block";
			}else{
				document.getElementById("widget-remove-app-loader").style.display = "none";
				document.getElementById("widget-remove-app-error").style.display = "block";
				document.getElementById("widget-remove-app-span-error").innerHTML = data.error;
			}
		})
		.catch(error => {
			document.getElementById("widget-remove-app-loader").style.display = "none";
			document.getElementById("widget-remove-app-error").style.display = "block";
			document.getElementById("widget-remove-app-span-error").innerHTML = error;
		})
}

function closeRemoveApp() {
	document.getElementById("widget-remove-app").style.display = "none";
	document.getElementById("overlay").style.display = "none";
}