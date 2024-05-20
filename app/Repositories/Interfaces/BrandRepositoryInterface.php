<?php

namespace App\Repositories\Interfaces;

use App\Models\Brand;

interface BrandRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function find($id);
}