<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\AdminsPopulate;
use Database\Populate\WorkersPopulate;

Database::migrate();
WorkersPopulate::populate();
AdminsPopulate::populate();
