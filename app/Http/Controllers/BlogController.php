<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\User;

class BlogController extends Controller
{
    public function index()
    {
        $blog = auth()->user()->blog;

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }

    public function show($id)
    {
        $blog = auth()->user()->blog()->find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Такого блога не сущеествует! '
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Успешно!',
            'zagalovok' => $blog->name,
            'text' => $blog->detail,
            'data sozdaniya' => $blog->created_at,
            'id avtora kotoryi sozdal' => $blog->user_id
        ], 400);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'detail' => 'required'
        ]);


        $blog = new Blog();
        $blog->name = $request->name;
        $blog->detail = $request->detail;

        if (auth()->user()->blog()->save($blog))
            return response()->json([
                'success' => true,
                'message' => 'Успешно!',
                'zagalovok' => $blog->name,
                'text' => $blog->detail,
                'data sozdaniya' => $blog->created_at


            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Блог не создано!'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $blog = auth()->user()->blog()->find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'только автор может изменить!'
            ], 400);
        }

        $updated = $blog->fill($request->all())->save();

        if ($updated)
            return response()->json([
                'success' => true,
                 'message' => 'Успешно!',
                 'zagalovok' => $blog->name,
                 'text' => $blog->detail,
                 'data izmenenie' => $blog->updated_at
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Блог не измененно!'
            ], 500);
    }

    public function destroy($id)
    {
        $blog = auth()->user()->blog()->find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Только автор может удалить!'
            ], 400);
        }

        if ($blog->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Успешено удалено!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Блог не удалено!'
            ], 500);
        }
    }
}
