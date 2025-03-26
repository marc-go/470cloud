function save() {
	const text = document.getElementById("content").value;
	const file = document.getElementById("js-tmp").innerHTML;

	fetch("bin/save.php?file=" + file + "&text=" + text)
	.then(response => response.json())
	.then(data => {
		alert("Erfolgreich");
		/* document.getElementById("js-msg-save").style.display = "block";
		setTimeout(function() {
			document.getElementById("js-msg-save").style.display = "none";
		}, 5000); */
	})
	.catch(error => alert(error));
}