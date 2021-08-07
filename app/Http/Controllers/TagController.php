<?php

namespace App\Http\Controllers;

//* models
use App\Models\Tag;

//* requests 
use App\Http\Requests\TagRequest;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return view('tags.index', ['tags' => $tags]);
    }

    public function show($tag_id)
    {
        $tag = Tag::findOrFail($tag_id);
        return view('tags.show', ['tag' => $tag]);
    }

    public function create()
    {
        $tags = Tag::all();
        return view('tags.create', ['tags' => $tags]);
    }

    public function store(TagRequest $request)
    {
        Tag::create($request->validated());
        // 提示訊息
        $message = "標籤新增成功";
        return redirect()->route('tags.index')->with("message", $message);
    }

    public function edit($tag_id)
    {
        $tag = Tag::findOrFail($tag_id);
        return view('tags.edit', ['tag' => $tag]);
    }

    public function update(TagRequest $request, $tag_id)
    {
        $tag = Tag::findOrFail($tag_id);

        $tag->update($request->validated());

        return redirect()->route('tags.index');
    }
}
