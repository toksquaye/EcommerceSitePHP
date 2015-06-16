<?php
    // configuration
    require("../includes/config.php");
    $rows = query("SELECT * FROM bags");
    echo($rows[0]["name"]);
?>