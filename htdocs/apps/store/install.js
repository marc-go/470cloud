function info(id) {
    fetch("https://api.470cloud.marc-goering.de/app/cloud/app-store/all.json")
        .then(response => JSON.parse(response))
        .then(data => {
            var app_data = data
        })
}