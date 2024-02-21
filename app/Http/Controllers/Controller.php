<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\News;
use App\Models\Gallery;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function register(Request $request)
    {
         $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users|email',
            'location' => 'required',
            'password' => 'required|min:6',
        ]);

        
      
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->location = $request->location;
        $user->password = Hash::make($request->password);
        $user->save();
        $userId = $user->id;

       
        return response(
            [
                 'user' => $user,
                 'message' => "User account successfully created",
          ]
        );
    }

    public function login(Request $request){
        $data = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $email = $request->email;
        $result = User::where('email',$email)->get();
        if ($result->first()) { 
            $result_password = $result[0]->password;
            if (Hash::check($request->password, $result_password)) {
                   $token = $result->first()->createToken('API Token')->plainTextToken;
           
            return response([
                "message" => 'Login successful',
                "user" => $result->first(),
                "token" => $token,
            
            ]);
            }
            else{
              return response(['message' => 'Incorrect Password .Please try again'],400);   
            }
        }
        else{
            return response(['message' => 'Incorrect email.Please try again'],400);  
         
        }
       

    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
          $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
          //$items = $items instanceof Collection ? $items : Collection::make($items);
          return new LengthAwarePaginator(collect($items)->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }

    public function createNews(Request $request){
        $title = $request->title;
        $content = $request->content;
        $description = $request->description;
        $author = $request->author_id;
        $image = $request->file('cover_img');

        $news_img_uploaded = $image->store('news_img', 'public');
        $news_img_uploaded_path = Storage::url($news_img_uploaded);
       
        $news = new News;
        $news->title = $title;
        $news->content = $content;
        $news->description = $description;
        $news->author_id = $author;
        $news->image = $news_img_uploaded_path;
        $save = $news->save();

        if($save){
            return response(['message' => 'Create news successful'],200);    
        }
        else{
            return response(['message' => 'Create news failed,an error has occured'],400);     
        }

    }

    public function getNews(){
        return $this->paginate(News::with('user')->get());
    }

    public function deleteNews(String $id){
        $post = News::where('id',$id)->first();
        $imagePath = $post->image;
        $path = "public".substr($imagePath,8);
  
         if(Storage::exists($path)){
              Storage::delete($path);
              News::where('id',$id)->delete();
              return response([ 'message' => "Successfully deleted news"],200);
              /*
                  Delete Multiple File like this way
                  Storage::delete(['upload/test.png', 'upload/test2.png']);
              */
        }
        else{
            return response([ 'message' => "The image path does not exist ".$path],400);
        }
    }

    public function addGallery(Request $request){
        $image = $request->file('gallery_img');
        $news_img_uploaded = $image->store('gallery_img', 'public');
        $news_img_uploaded_path = Storage::url($news_img_uploaded);

        $gallery = new Gallery;
        $gallery->image = $news_img_uploaded_path;
        $save = $gallery->save();

        if($save){
            return response(['message' => 'Successfully added picture to gallery'],200);    
        }
        else{
            return response(['message' => 'An error has occured,please check network'],400);     
        }
    }

    public function getGallery(){
        return $this->paginate(Gallery::all());
    }

    public function deleteGallery(String $id){
        $pic = Gallery::where('id',$id)->first();
        $imagePath = $pic->image;
        $path = "public".substr($imagePath,8);
  
         if(Storage::exists($path)){
              Storage::delete($path);
              Gallery::where('id',$id)->delete();
              return response([ 'message' => "Successfully deleted picture from gallery"],200);
              /*
                  Delete Multiple File like this way
                  Storage::delete(['upload/test.png', 'upload/test2.png']);
              */
        }
        else{
            return response([ 'message' => "The image path does not exist ".$path],400);
        }
    }

    public function getBlogCategories(){
        return BlogCategory::all();
    }

    public function addBlogCategory(Request $request){
        $name = $request->name;
        $description = $request->description;
        $image = $request->file('cover_img');

        $news_img_uploaded = $image->store('blog_category_img', 'public');
        $news_img_uploaded_path = Storage::url($news_img_uploaded);
       
        $blog_category = new BlogCategory;
        $blog_category->name = $name;
       
        $blog_category->description = $description;
       
        $blog_category->image = $news_img_uploaded_path;
        $save = $blog_category->save();

        if($save){
            return response(['message' => 'Create blog category successful'],200);    
        }
        else{
            return response(['message' => 'Create blog category failed,an error has occured'],400);     
        }
    }

    public function addBlog(Request $request){
        $title = $request->name;
        $description = $request->description;
        $image = $request->file('cover_img');

        $news_img_uploaded = $image->store('blog_category_img', 'public');
        $news_img_uploaded_path = Storage::url($news_img_uploaded);
       
        $blog_category = new BlogCategory;
        $blog_category->name = $name;
       
        $blog_category->description = $description;
       
        $blog_category->image = $news_img_uploaded_path;
        $save = $blog_category->save();

        if($save){
            return response(['message' => 'Create blog category successful'],200);    
        }
        else{
            return response(['message' => 'Create blog category failed,an error has occured'],400);     
        }
    }
}
