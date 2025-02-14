<?php

namespace App\Controllers;

use App\Models\Position;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

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
            $errors = $position->errors;
            $_SESSION['errors'] = $errors;
            $this->redirectTo(route('positions.create'));
            return;
        }

        if ($position->save()) {
            $this->redirectTo(route('admins.positions'));
            return;
        }

        $this->redirectTo(route('positions.create'));
    }
}
