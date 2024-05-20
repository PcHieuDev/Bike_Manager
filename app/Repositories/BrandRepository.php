<?php
namespace App\Repositories;

use App\Models\Brand;
use App\Repositories\Interfaces\BrandRepositoryInterface;

class BrandRepository implements BrandRepositoryInterface
{
    public function all()
    {
        return Brand::all();
    }

    public function create(array $data)
    {
        return Brand::create($data);
    }

    public function update(array $data, $id)
    {
        return Brand::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return Brand::destroy($id);
    }

    public function find($id)
    {
        return Brand::find($id);
    }

    


    
}

