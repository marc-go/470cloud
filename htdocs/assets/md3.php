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
        --md-sys-color-primary:rgb(46, 131, 236);
        --md-ref-typeface-brand: "roboto", sans-serif;
        --md-ref-typeface-plain: system-ui;
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<style>
  md-icon {
    font-family: "Material Symbols Outlined";
    font-size: 24px;
    font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
  }
</style>
<script src="/assets/menue.js"></script>
<script>
    fetch("/apps/home")
    fetch("/apps/files")
    fetch("/apps/reminders")
    fetch("/apps/settings")
</script>
<script src="https://cdn.jsdelivr.net/npm/eruda"></script>
<script>eruda.init();</script>
';
