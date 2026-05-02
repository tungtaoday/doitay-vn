<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Cache::remember('public.categories.v1', 3600, function () {
            return Category::query()
                ->where('status', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'icon', 'image'])
                ->map(fn ($c) => [
                    'id'    => $c->id,
                    'name'  => $c->name,
                    'icon'  => $c->icon,
                    'image' => $c->image,
                ])
                ->values();
        });

        return response()->json(['data' => $data]);
    }
}
