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
    :root{
        --md-sys-color-primary: #ECAA2E;
    }
</style>
';