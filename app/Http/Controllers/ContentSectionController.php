<?php

// app/Http/Controllers/ContentSectionController.php
namespace App\Http\Controllers;

use App\Models\ContentSection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContentSectionController extends Controller
{
    // GET /api/sections             -> list (for menus)
    public function index(Request $req) {
        $q = ContentSection::query()->when($req->boolean('published', null) !== null, fn($qq) =>
            $qq->where('published', $req->boolean('published'))
        );
        return response()->json($q->select('id','name','slug','published','updated_at')->orderBy('name')->get());
    }

    // GET /api/sections/{slug}      -> one section
    public function show(string $slug) {
        $section = ContentSection::where('slug', $slug)->where('published', true)->firstOrFail();
        return response()->json($section);
    }

    // POST /api/sections            -> create/update via JSON
    public function store(Request $req) {
        $data = $req->validate([
            'id'        => ['nullable','integer','exists:content_sections,id'],
            'name'      => ['required','string','max:255'],
            'slug'      => ['required','string','max:255', Rule::unique('content_sections','slug')->ignore($req->id)],
            'content'   => ['required','array'], // trust frontend renderer by "kind"
            'published' => ['boolean'],
        ]);

        $section = ContentSection::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'name' => $data['name'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'published' => $data['published'] ?? true,
            ]
        );

        return response()->json($section, $req->filled('id') ? 200 : 201);
    }
}

