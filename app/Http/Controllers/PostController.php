<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    protected $numPages;

    public function __construct() {
        $this->numPages = 3;
    }

    public function index() {

        $posts = Post::latest()->paginate($this->numPages);
        // $posts = Post::orderBy('id', 'DESC')->paginate(3);

        // $posts = Post::get();
        // $posts = Post::all();

        // return view('admin.posts.index', [
        //     'posts' => $posts
        // ]);

        // dd($posts);
        return view('admin.posts.index', compact('posts')); //Assim ou da forma anterior
    }

    public function create() {
        return view('admin.posts.create');
    }

    public function store(StoreUpdatePost $request) {
        // $request->all(); //traz todo conteúdo da requisição
        // $request->title;
        // $request->file('image');
        $data = $request->all();
        $dir  = 'public/';

        if($request->image->isValid()) {
            $nameFile      = Str::of($request->title)->slug('-') . '.' . $request->image->getClientOriginalExtension();
            $image         = $request->image->storeAs($dir . 'posts', $nameFile);
            $image         = str_replace($dir, '', $image);
            $data['image'] = $image;
        }

        Post::create($data);

        return redirect()
            ->route('posts.index')
            ->with('message', 'Post criado com sucesso!');
    }

    public function show($id) {
        //Post::where('id', $id)->get(); //Retorna uma colection, um array com todos
        //$post = Post::where('id', $id)->first(); //Retorna a primeira ocorrência

        if (!$post = Post::find($id))
            return redirect()
                ->route('posts.index')
                ->with('message', 'Post não encontrado');

        return view('admin.posts.show', compact('post'));
    }

    public function destroy($id) {
        if(!$post = Post::find($id))
            return redirect()->route('posts.index');

        $dir = 'public/';
        
        if(Storage::exists($dir . $post->image))
            Storage::delete($dir . $post->image);

        $post->delete();
        return redirect()
            ->route('posts.index')
            ->with('message', 'Post deletado com sucesso!');
    }

    public function edit($id) {
        if(!$post = Post::find($id))
            return redirect()
                    ->route('posts.index')
                    ->with('message', 'Registro não encontrado!');

        return view('admin.posts.edit', compact('post'));
    }

    public function update(StoreUpdatePost $request, $id) {

        if(!$post = Post::find($id))
            return redirect()->back();
            
        $data = $request->all();
        $dir  = 'public/';

        if($request->image && $request->image->isValid()) {
            
            if(Storage::exists($dir . $post->image))
                Storage::delete($dir . $post->image);

            $nameFile      = Str::of($request->title)->slug('-') . '.' . $request->image->getClientOriginalExtension();
            $image         = $request->image->storeAs($dir . 'posts', $nameFile);
            $image         = str_replace($dir, '', $image);
            $data['image'] = $image;
        }

        $post->update($data);

        return redirect()
            ->route('posts.index')
            ->with('message', 'Post atualizado com sucesso');
    }

    public function search(Request $request) {
        $filters = $request->except('_token');

        // $posts = Post::where('name', $request->search);
        $posts = Post::where('title', '=', "{$request->search}")
                    ->orWhere('content', 'LIKE', "%{$request->search}%")
                    // ->toSql()
                    ->paginate($this->numPages);

        return view('admin.posts.index', compact('posts', 'filters'));
    }

}