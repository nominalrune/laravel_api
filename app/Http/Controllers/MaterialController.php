<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use Illuminate\Foundation\Http\FormRequest;

class MaterialController extends Controller
{
    public function index()
    {
        $materials=Material::pagenate(50);
        return $materials;
    }
    public function show(Material $material)
    {
        return $material;
    }
    public function store(FormRequest $request)
    {
        $material=Material::create($request->validated());
        return $material;
    }
    public function update(FormRequest $request, Material $material)
    {
        $material->update($request->validated());
        return $material;
    }
    public function delete(Material $material)
    {
        $material->delete();
        return response()->json(null, 204);
    }
}
