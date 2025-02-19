<?php

namespace App\Controllers;

use App\Models\Position;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class PositionController extends Controller
{
    protected string $layout = 'admin';

    public function positions(Request $request): void
    {
        $paginator = Position::paginate(page: $request->getParam('page', 1), per_page: 4);
        $positions = $paginator->registers();
        $this->render('admin/positions/index', compact('positions', 'paginator'));
    }

    public function create(): void
    {
        $this->render('admin/positions/create');
    }

    public function store(Request $request): void
    {
        $position = new Position($request->getParams()['position']);

        if (!$position->isValid()) {
            FlashMessage::danger('Existem dados incorretos! Por favor verifique!');
            $errors = $position->errors;
            $_SESSION['errors'] = $errors;
            $this->redirectTo(route('positions.create'));
            return;
        }

        if ($position->save()) {
            FlashMessage::success('Cargo criado com sucesso!');
            $this->redirectTo(route('positions.positions'));
            return;
        }

        FlashMessage::danger('Existem dados incorretos! Por favor verifique!');
        $this->redirectTo(route('positions.create'));
    }

    public function destroy(Request $request): void
    {
        $id = $request->getParam('id');

        $worker = Position::findById($id);
        $worker->destroy();

        FlashMessage::success('Cargo deletado com sucesso!');
        $this->redirectTo(route('positions.positions'));
    }
}
