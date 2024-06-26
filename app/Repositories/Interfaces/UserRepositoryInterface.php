<?php

namespace App\Repositories\Interfaces;


use App\Models\Category;

interface UserRepositoryInterface
{
   
    public function all();

    public function get(int $id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}