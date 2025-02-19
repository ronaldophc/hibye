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
            $this->redirectTo(route('workers.workers'));
            return;
        }

        FlashMessage::danger('Existem dados incorretos! Por favor verifique!');
        $this->redirectTo(route('workers.create'));
    }

    public function edit(Request $request): void
    {
        $worker = Worker::findById($request->getParam('id'));
        if ($worker === null) {
            FlashMessage::danger('Funcionário não encontrado!');
            $this->redirectTo(route('workers.admins'));
            return;
        }
        $positions = Position::all();
        $this->render('admin/workers/edit', compact('worker', 'positions'));
    }

    public function destroy(Request $request): void
    {
        $id = $request->getParam('id');

        $worker = Worker::findById($id);
        $worker->destroy();

        FlashMessage::success('Funcionário removido com sucesso!');
        $this->redirectTo(route('workers.workers'));
    }

    public function update(Request $request): void
    {
        $worker = Worker::findById($request->getParam('id'));
        $params = $request->getParams()['worker'];

        if ($worker === null) {
            FlashMessage::danger('Funcionário não encontrado!');
            $this->redirectTo(route('workers.workers'));
            return;
        }

        $update_params = array_filter($params, fn($value, $key) => $key !== 'password' || $value !== $worker->password, ARRAY_FILTER_USE_BOTH);
        $worker->setAttributes($update_params);

        if (!$worker->isValidUpdate()) {
            FlashMessage::danger('Existem dados incorretos! Por favor verifique!');
            $errors = $worker->errors;
            $_SESSION['errors'] = $errors;
            $this->redirectTo(route('workers.edit', ['id' => $worker->id]));
            return;
        }

        if ($worker->update($update_params)) {
            FlashMessage::success('Funcionário atualizado com sucesso!');
            $this->redirectTo(route('workers.workers'));
            return;
        }

        FlashMessage::danger('Dados incompletos! Verifique!');
        $errors = $worker->errors;
        $_SESSION['errors'] = $errors;
        $this->redirectTo(route('workers.edit', ['id' => $worker->id]));
    }
}
