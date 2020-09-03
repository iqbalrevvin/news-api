<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\News;
use App\Category;

class NewsController extends Controller
{
    public function index(Request $request)
    {
    	$keyword = $request->has('keyword');
    	if($request->has('keyword')){
    		$news = News::where('status', 'Publish')->where('title','like','%'.$request->keyword.'%')->get();
    	}else{
    		$news = News::where('status', 'Publish')->get();
    	}

    	foreach($news as $key => $value){
    		$data[] = [
    			'id' 			=> $value->id,
    			'title' 		=> $value->title,
    			'image' 		=> $value->image,
    			'date' 			=> $value->date,
    			'author' 		=> $value->author->name,
    			'category_id' 	=> $value->category_id,
    			'category' 		=> $value->category->name,
    			'created_at' 	=> $value->created_at->diffForHumans(),
    		];
    	}

    	$response = response()->json([
	        'status' => 'success',
	        'message' => 'News List',
	        'data' => [
	        	'news' => $data
	        ]
	    ], 200);

	    return $response;
    }

    public function Detail($id)
    {
    	$detail_news = News::where('id', $id)->first();

    	$data = [
			'id' 		=> $detail_news->id,
			'title' 	=> $detail_news->title,
			'content' 	=> $detail_news->content, 
			'date' 		=> $detail_news->date,
			'author' 	=> $detail_news->author->name,
			'category' 	=> $detail_news->category->name,
			'created_at' => $detail_news->created_at->diffForHumans()
		];

    	$response = response()->json([
	        'status' => 'success',
	        'message' => 'News Detail',
	        'data' => [
	        	'news' => $data
	        ]
	    ], 200);

	    return $response;

    }

    public function CategoryList()
    {
    	$category = Category::where('status', 'Active')->get();

    	$response = response()->json([
	        'status' => 'success',
	        'message' => 'News Detail',
	        'data' => [
	        	'category' => $category
	        ]
	    ], 200);

	    return $response;

    }
}
