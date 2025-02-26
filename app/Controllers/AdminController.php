<?php

namespace App\Controllers;

use App\Models\Admin;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class AdminController extends Controller
{
    protected string $layout = 'admin';

    public function index(): void
    {
        $this->render('admin/index');
    }

    public function admins(Request $request): void
    {
        $paginator = Admin::paginate(page: $request->getParam('page', 1), per_page: 4);
        $admins = $paginator->registers();
        $this->render('admin/admins/index', compact('admins', 'paginator'));
    }

    public function create(): void
    {
        $this->render('admin/admins/create');
    }

    public function store(Request $request): void
    {
        $admin = new Admin($request->getParams()['admin']);

        if (!$admin->isValid()) {
            FlashMessage::danger('Dados incompletos! Verifique!');
            $this->redirectTo(route('admins.create'));
            return;
        }

        $uploadedFile = $_FILES['profile_image'];

        if ($admin->save()) {
            FlashMessage::success('Admin registrado com sucesso!');
            if ($uploadedFile) {
                $admin->profileImage()->update($uploadedFile);
            }
            $this->redirectTo(route('admins.admins'));
            return;
        }

        FlashMessage::danger('Existem dados incorretos! Por favor verifique!');
        $this->redirectTo(route('admins.create'));
    }

    public function edit(Request $request): void
    {
        $id = $request->getParam('id');
        $admin = Admin::findById($id);

        if ($admin === null) {
            FlashMessage::danger('Admin não encontrado!');
            $this->redirectTo(route('admins.admins'));
            return;
        }

        $this->render('admin/admins/edit', compact('admin'));
    }

    public function update(Request $request): void
    {
        $id = $request->getParam('id');
        $admin = Admin::findById($id);

        if ($admin === null) {
            FlashMessage::danger('Admin não encontrado!');
            $this->redirectTo(route('admins.admins'));
            return;
        }

        $password = $request->getParam('password');
        $admin->password = $password;

        if (!$admin->isValidUpdate()) {
            FlashMessage::danger('Dados incompletos! Verifique!');
            $errors = $admin->getErrors();
            $_SESSION['errors'] = $errors;
            $this->redirectTo(route('admins.edit', ['id' => $id]));
            return;
        }

        $uploadedFile = $_FILES['profile_image'];
        if ($uploadedFile) {
            $profileImage = $admin->profileImage();
            if (!$profileImage->validate($uploadedFile)) {
                $_SESSION['image_error'] = $profileImage->getErrors();
                $this->redirectTo(route('admins.edit', ['id' => $id]));
            }
            $admin->profileImage()->update($_FILES['profile_image']);
        }

        if ($admin->update(['password' => $password])) {
            FlashMessage::success('Admin atualizado com sucesso!');
        }

        $this->redirectTo(route('admins.admins'));
    }

    public function destroy(Request $request): void
    {
        $id = $request->getParam('id');

        $admin = Admin::findById($id);
        $admin->destroy();

        FlashMessage::success('Admin removido com sucesso!');
        $this->redirectTo(route('admins.admins'));
    }
}
