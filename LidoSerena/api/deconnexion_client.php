<?php
session_start();
session_destroy();
header("Location: ../nathan/co_clients.html");
exit();
