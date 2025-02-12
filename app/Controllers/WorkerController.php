<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Worker;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class WorkerController extends Controller
{
    protected string $layout = 'admin';

    public function workers(Request $request): void
    {
        $paginator = Worker::paginate(page: $request->getParam('page', 1), per_page: 4);
        $workers = $paginator->registers();
        $this->render('admin/workers/index', compact('workers', 'paginator'));
    }
}
