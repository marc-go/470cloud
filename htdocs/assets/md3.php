<?php
echo '
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<script type="importmap">
    {
        "imports": {
                "@material/web/": "https://esm.run/@material/web/"
        }
    }
</script>
<script type="module">
    import "@material/web/all.js";
    import {styles as typescaleStyles} from "@material/web/typography/md-typescale-styles.js";

    document.adoptedStyleSheets.push(typescaleStyles.styleSheet);
</script>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Open%20Sans:wght@400;500;700&display=swap");

    :root{
        --md-sys-color-primary: #ECAA2E;
        --md-ref-typeface-brand: "Open Sans";
        --md-ref-typeface-plain: system-ui;
    }
</style>
<script src="/assets/menue.js"></script>
<script>
    fetch("/apps/home")
    fetch("/apps/files")
    fetch("/apps/reminders")
    fetch("/apps/settings")
</script>
';
