<?php

namespace App\Services;

use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;
use Core\Database\Database;

class ProfileImage
{
    /** @var array<string, mixed> $image */
    private array $image;

    /**
     * @var string[] Array de extensões permitidas
     */
    protected array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    protected int $maxFileSize = 5000000;

    /**
     * @var array<int, string> Array indexado com mensagens de erro
     */
    protected array $errors = [];

    public function __construct(
        private readonly Model $model
    ) {
    }

    public function path(): string
    {
        if ($this->model->profile_image) {
            $hash = md5_file($this->getAbsoluteSavedFilePath());
            return $this->baseDir() . $this->model->profile_image . '?' . $hash;
        }

        return "/assets/images/profile-image.png";
    }

    /**
     * @param array<string, mixed> $image
     */
    public function update(array $image): void
    {
        $this->image = $image;

        if (!empty($this->getTmpFilePath())) {
            $pdo = Database::getDatabaseConn();
            $pdo->beginTransaction();

            try {
                $this->removeOldImage();

                if (!move_uploaded_file($this->getTmpFilePath(), $this->getAbsoluteDestinationPath())) {
                    throw new \RuntimeException('Failed to move uploaded file');
                }

                $this->model->update(['profile_image' => $this->getFileName()]);

                $pdo->commit();
            } catch (\Exception $e) {
                $pdo->rollBack();
                throw $e;
            }
        }
    }

    private function getTmpFilePath(): string
    {
        return $this->image['tmp_name'];
    }

    private function removeOldImage(): void
    {
        if ($this->model->profile_image) {
            $path = Constants::rootPath()->join('public' . $this->baseDir())->join($this->model->profile_image);
            unlink($this->getAbsoluteSavedFilePath());
        }
    }

    private function getFileName(): string
    {
        $file_name_splitted  = explode('.', $this->image['name']);
        $file_extension = end($file_name_splitted);
        return 'image.' . $file_extension;
    }

    private function getAbsoluteDestinationPath(): string
    {
        return $this->storeDir() . $this->getFileName();
    }

    private function baseDir(): string
    {
        return "/assets/uploads/{$this->model::table()}/{$this->model->id}/";
    }

    private function storeDir(): string
    {
        $path = Constants::rootPath()->join('public' . $this->baseDir());
        if (!is_dir($path)) {
            mkdir(directory: $path, recursive: true);
        }

        return $path;
    }

    private function getAbsoluteSavedFilePath(): string
    {
        return Constants::rootPath()->join('public' . $this->baseDir())->join($this->model->profile_image);
    }

    public function getErrors(): string
    {
        return implode(', ', $this->errors);
    }

    /**
     * Valida uma imagem de upload.
     *
     * @param array{name: string, tmp_name: string, size: int, error: int} $image Dados do arquivo $_FILES
     * @return bool Retorna true se a validação passar
     */

    public function validate(array $image): bool
    {
        $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($fileExtension), $this->allowedExtensions, true)) {
            $this->errors[] = 'Extensão de arquivo inválida! Utilize uma das seguintes extensões: '
            . implode(', ', $this->allowedExtensions) . '.';
            return false;
        }

        if ($image['size'] > $this->maxFileSize || $image['size'] === 0) {
            $this->errors[] = 'O arquivo excede o tamanho máximo permitido de '
            . ($this->maxFileSize / 1000000) . ' MB.';
            return false;
        }

        return true;
    }
}
