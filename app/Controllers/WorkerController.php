<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Position;
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

    public function create(Request $request): void
    {
        $positions = Position::all();
        $this->render('admin/workers/create', compact('positions'));
    }

    public function store(Request $request): void
    {
        $worker = new Worker($request->getParams()['worker']);

        if (!$worker->isValid()) {
            FlashMessage::danger('Dados incompletos! Verifique!');
            $errors = $worker->errors;
            $_SESSION['errors'] = $errors;
            $this->redirectTo(route('workers.create'));
            return;
        }

        if ($worker->save()) {
            FlashMessage::success('Funcionário registrado com sucesso!');
            $this->redirectTo(route('admins.workers'));
            return;
        }

        FlashMessage::danger('Existem dados incorretos! Por favor verifique!');
        $this->redirectTo(route('workers.create'));
    }
}
