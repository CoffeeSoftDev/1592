<?php
session_start();

if (!empty($_COOKIE["IDU"])) {
    header("Location: coffee/");
    exit();
}

header("Location: acceso/");
exit();
