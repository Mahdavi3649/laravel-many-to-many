<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;
use App\Mail\NewPostCreated;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->get();
        //dd($posts);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        //dd($categories);
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //ddd($request->all());
        //validate data
        $validate_data = $request->validated();
    
        // generate the slug
        $slug = Post::generateSlug($request->title);

        $validate_data['slug'] =$slug;
        //dd($validate_data);
        //$validate_data['category_id'] = $request->category_id;
        

        //verificare se la richiesta contiene un file 
        
        //ddd(array_key_exists('cover_image', $request->all())); // opzione 1
        //opzione 2
        if($request->hasFile('cover_image')) {

            // valida il file 
            $request->validate([
                'cover_image' => 'nullable|image|max:250' 
            ]);
            // la salvo nel filesystem
            //ddd($request->all());
            //recrupero il percorso 
            $path = Storage::put('post_images', $request->cover_image);
            //ddd($path);
            //pass il percorso all'array di dati validati per il salvataggio  della risorsa
            $validate_data['cover_image'] = $path;
        }

        //ddd($validate_data);

        //create the resource
        $new_post = Post::create($validate_data);
        $new_post->tags()->attach($request->tags);

        //return (new NewPostCreated($new_post))->render();

        // redirect to get route
        return redirect()->route('admin.posts.index')->with('message','Post Created Successfully');

        //invia la mail usando istanza dell utente nella request
        Mail::to($request->user())->send(new NewPostCreated($new_post));

         //invia la mail usando una mail
         //Mail::to('test@example.com')->send(new NewPostCreated($new_post));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        //ddd($request->all());

        // validate data
        $validate_data = $request->validated();
        //dd($validate_data);
        // Gererate the slug
        $slug = Post::generateSlug($request->title);
        //dd($slug);
        $validate_data['slug'] = $slug;

        if($request->hasFile('cover_image')) {

            // valida il file 
            $request->validate([
                'cover_image' => 'nullable|image|max:250' 
            ]);

            Storage::delete($post->cover_image);
            
            // la salvo nel filesystem
            //ddd($request->all());
            //recrupero il percorso 
            $path = Storage::put('post_images', $request->cover_image);
            //ddd($path);
            //pass il percorso all'array di dati validati per il salvataggio  della risorsa
            $validate_data['cover_image'] = $path;
        }


        // update the resource
        $post->update($validate_data);

        $post->tags()->sync($request->tags);

        // redirect to get route
        return redirect()->route('admin.posts.index')->with('message', "$post->title updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        Storage::delete($post->cover_image);
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', "$post->title deleted successfully");
    }
}
