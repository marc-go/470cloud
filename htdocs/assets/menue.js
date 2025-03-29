tabs.addEventListener('change', (event: Event) => {
    if (event.target.activeTabIndex === 1) {
        setTimeout(function() {
            window.location.href = "/apps/home"
        }, 1000);
    }

    if (event.target.activeTabIndex === 2) {
        setTimeout(function() {
            window.location.href = "/apps/files"
        }, 1000);
    }

    if (event.target.activeTabIndex === 3) {
        setTimeout(function() {
            window.location.href = "/apps/reminders"
        }, 1000);
    }

    if (event.target.activeTabIndex === 4) {
        setTimeout(function() {
            window.location.href = "/apps/settings"
        }, 1000);
    }
});