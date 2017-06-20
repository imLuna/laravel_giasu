<?php

namespace App\Http\Controllers\Quantri\news;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\News;
use DateTime;

class listController extends Controller
{
    public function listNews()
    {	
    	$data=News::get()->toArray();
    	$cate=Category::get()->toArray();
    	return view('quantri.news.list',['data'=>$data,'cate'=>$cate]);
    }
    public function addNews(Request $request)
    {
		$news             = new News;
		$news->name       = $request->name;
		$news->slug       = str_slug($request->name,"-");
		$news->cat_id     = $request->cat_id;
		$news->intro      = $request->intro;
		$news->content    = $request->contents;
		$news->status     = $request->status;
		$img              = $request->file('image');
            if($request->image != null){
                $name=time()."-".$img->getClientOriginalName();
                $path="HinhAnh/Quantri/News/";
                $img->move($path,$name);
                $news->image=$name;
            }
		$news->user_id    = Auth::user()->id;
		$news->created_at = new DateTime();
    	$news->save();
    	return redirect()->route('listNews')->with(['flash_level'=>'flash_msg','flash_message'=>'Add item success !']);
    }
    public function editNews($id,Request $request)
    {
        $news             = News::find($id);
        $news->name       = $request->name;
        $news->slug       = str_slug($request->name,"-");
        $news->cat_id     = $request->cat_id;
        $news->intro      = $request->intro;
        $news->content    = $request->contents;
        $news->status     = $request->status;
        $img              = $request->file('image');
            if($request->hasFile('image')){
                $acu=$request->acu;
                $pcu="/HinhAnh/Quantri/News/";
                if(file_exists(public_path().$pcu.$acu)){
                    File::delete(public_path().$pcu.$acu);
                }
                $name=time()."-".$img->getClientOriginalName();
                $path="HinhAnh/Quantri/News/";
                $img->move($path,$name);
                $news->image=$name;
            }else{
                $news->image=$request->acu;
            }
        $news->user_id    = Auth::user()->id;
        $news->updated_at = new DateTime();
        $news->save();
        return redirect()->route('listNews')->with(['flash_level'=>'flash_msg','flash_message'=>'Edit item success !']);
    }
    public function deleteNews($id){
        $news = News::find($id);
        $path="/HinhAnh/Quantri/News/";
        if(file_exists(public_path().$path.$news->image)){
            File::delete(public_path().$path.$news->image);
        }
        $news->delete();
        return redirect()->route('listNews')->with(['flash_level'=>'flash_msg','flash_message'=>'Delete item success !']);
    }
}
