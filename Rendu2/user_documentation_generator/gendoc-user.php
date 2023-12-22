#!/usr/bin/php

<?php
    $lines = file("doc.md");

    $content_md = implode("\n", $lines);

    file_put_contents("doc-user-1.0.0.html", $content_md);
?>