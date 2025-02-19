<?php

namespace Core\Database\ActiveRecord;

class BelongsTo
{
    public function __construct(
        private Model $model,
        private string $related,
        private string $foreignKey
    ) {
    }

    public function get(): Model | null
    {
        $attribute = $this->foreignKey;
        return $this->related::findBy(['id' => $this->model->$attribute]);
    }
}
