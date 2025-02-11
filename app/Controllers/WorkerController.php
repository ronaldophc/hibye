<?php

namespace App\Controllers;

use App\Models\Admin;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class WorkerController extends Controller
{
    protected string $layout = 'admin';

    public function workers(): void
    {
        $this->render('admin/workers/index');
    }
}
