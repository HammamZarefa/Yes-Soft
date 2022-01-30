<?php

namespace App\Repositories;

use App\Events\PostEvent;
use App\Http\Requests\Post\PostRequest;
use App\Models\Category\Category;
use App\Models\Post\Post;
use App\Models\Post\PostTranslation;
use App\Models\Tag\Tag;
use App\Models\User\User;
use App\Notifications\AddPostNotification;
use App\Notifications\PostNotification;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class PostRepository implements PostRepositoryInterface{

    private $request;
    private $post;
    private $postTranslation;
    private $category;
    private $tag;
    public function __construct(Post $post , PostTranslation $postTranslation , Category $category , Tag $tag, Request $request)
    {
        $this->request = $request;
        $this->post = $post;
        $this->postTranslation = $postTranslation;
        $this->category = $category;
        $this->tag = $tag;
    }

    public function index()
    {
        $post = $this->post::orderBy('id','desc')->get();

        return view('admin.post.index',compact('post'));
    }

    public function create()
    {

        $categories = $this->category::get();
        $tags = $this->tag::get();

        return view('admin.post.create',compact('categories','tags'));
    }

    public function store(PostRequest $request)
    {

        try {
            // /** transformation to collection */
            $allposts = collect($request->post)->all();

            $slug= $this->request->post['en']['title'];

            // return $id=$allposts->id;
            $request->is_active ? $is_active = true : $is_active = false;

            $cover = $request->file('cover');
            if($cover){
            $cover_path = $cover->store('images/blog', 'public');
            }

            // return tags()->attach(request('tags'));

            DB::beginTransaction();
            //create the default language's post
            $unTransPost_id = $this->post->insertGetId([
                'category_id' => $request['category'],
                'author_id' => Auth::user()->id,
                'slug' => $slug,
                'cover' => $cover_path,
                'is_active' => $request->is_active = 1,
                'status' => 'PUBLISH',
                // 'tags' =>  $request['tags'],
            ]);

            // if($request->has('tags')){
            //     $post= $this->post::find($unTransPost_id);
            //     $post->tags()->syncWithoutDetaching($request->get('tags'));
            //  }

            //check the Post and request
            if (isset($allposts) && count($allposts)) {
                //insert other traslations for Posts
                foreach ($allposts as $allpost) {
                    $transPost_arr[] = [
                        'title' => $allpost ['title'],
                        'local' => $allpost['local'],
                        'body' => $allpost['body'],
                        'keyword' => $allpost['keyword'],
                        'meta_desc' => $allpost['meta_desc'],
                        'post_id' => $unTransPost_id
                    ];
                }
                $this->postTranslation->insert($transPost_arr);
            }

             DB::commit();

            $notification=Post::find($unTransPost_id);
            event(new PostEvent($notification));

            return redirect()->route('admin.post')->with('success', 'Data added successfully');

        } catch (\Exception $ex) {
            // return $ex->getMessage();
            DB::rollback();
            return redirect()->route('admin.post.create')->with('error', 'Data failed to add');
        }


    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $post = $this->post::findOrFail($id);
        $categories = $this->category::get();
        $tags = $this->tag::get();
        return view('admin.post.edit',compact('post','categories','tags'));
    }

    public function update(PostRequest $request,$id)
    {
        try{

            $post = $this->post::findOrFail($id);

            $slug= $request->post['en']['title'];

        // image desktop
        $new_cover = $request->file('cover');

        if($new_cover){
            if($request->cover && file_exists(storage_path('app/public/' .$request->cover))){
                Storage::delete('public/'. $request->cover);
            }
            $new_cover_path = $new_cover->store('images/blog', 'public');
        }

            DB::beginTransaction();
            // //create the default language's post
            $unTransPost_id = $this->post->where('posts.id', $post->id)
                ->update([
                    'slug' => $slug,
                    'category_id' => $request['category'],
                    'author_id' => Auth::user()->id,
                    'is_active' => $request->is_active = 1,
                    'cover' => $new_cover_path,
                    // 'tags' =>  $this->post->tags()->sync(request('tags'));
            ]);

            // $post->tags()->sync(request('tags'));
            $allposts = array_values($this->request->post);
                //insert other translations for Post
                foreach ($allposts as $allpost) {
                    $this->postTranslation->where('post_id', $id)
                    ->where('local', $allpost['local'])
                    ->update([
                        'title' => $allpost ['title'],
                        'local' => $allpost['local'],
                        'body' => $allpost['body'],
                        'keyword' => $allpost['keyword'],
                        'meta_desc' => $allpost['meta_desc'],
                        'post_id' =>  $unTransPost_id
                    ]);
                }
            DB::commit();
            return redirect()->route('admin.post')->with('success', 'Data added successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.post.create')->with('error', 'Data failed to add');
        }
    }

    public function destroy($id)
    {
        $post = $this->post::findOrFail($id);

        $post->delete();

        return redirect()->route('admin.post')->with('success','Post moved to trash');
    }

    public function trash(){
        $post = $this->post::onlyTrashed()->get();

        return view('admin.post.trash', compact('post'));
    }

    public function restore($id) {
        $post = $this->post::withTrashed()->findOrFail($id);

        if ($post->trashed()) {
            $post->restore();
            return redirect()->route('admin.post.trash')->with('success','Data successfully restored');
        }else {
            return redirect()->route('admin.post.trash')->with('error','Data is not in trash');
        }
    }

    public function deletePermanent($id){

        $post = $this->post::withTrashed()->findOrFail($id);

        if (!$post->trashed()) {

            return redirect()->route('admin.post.trash')->with('error','Data is not in trash');

        }else {

            $post->tags()->detach();


            if($post->cover && file_exists(storage_path('app/public/' . $post->cover))){
                Storage::delete('public/'. $post->cover);
            }

        $post->forceDelete();

        return redirect()->route('admin.post.trash')->with('success', 'Data deleted successfully');
        }
    }

    public function MarkNotification(){
        foreach(auth()->user()->unreadNotifications as $notification){
            $notification->markAsRead();
        }
    }
}
