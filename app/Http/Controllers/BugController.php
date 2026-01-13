<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBugRequest;
use App\Models\Bug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BugController extends Controller
{
    public function index()
    {
        $data = Bug::paginate();

        return response()->json($data, 200);
    }

    public function store(StoreBugRequest $request)
    {
        $data = Bug::create($request->validated());

        return response()->json($data, 201);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'id' => $id,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'status' => $request->status,
            'created_at' => now()->toISOString(),
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $bug = Bug::find($id);

        if (! $bug) {
            return response()->json(['message' => 'Bug não encontrado.'], 404);
        }

        $bug->delete();

        return response()->json(['message' => 'Bug deletado com sucesso.'], 204);
    }
}
