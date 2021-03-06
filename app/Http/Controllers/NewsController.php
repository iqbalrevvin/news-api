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
    		$news = News::select('news.id as id', 'news.title','news.image', 'author.name as author_name', 'category.id as category_id', 'category.name as category_name', 'news.created_at')->join('category', 'category.id', '=', 'news.category_id')
    					->join('author', 'author.id', '=', 'news.author_id')
    					->where('news.status', 'Publish')->where('news.title','like','%'.$request->keyword.'%')
    					->orWhere('category.name','like','%'.$request->keyword.'%')
    					->orderBy('news.created_at', 'desc')->get();

    	}else{
    		$news = News::where('status', 'Publish')->orderBy('created_at', 'desc')->get();
    	}

    	$banners = News::where('status', 'Publish')->inRandomOrder()->limit(5)->get();
    	foreach ($banners as $key => $value) {
    		$data_banner[] = [
    			'id' => $value->id,
    			'title' => $value->title,
    			'image' => $value->image
    		];
    	}

    	foreach($news as $key => $value){
    		$data[] = [
    			'id' 			=> $value->id,
    			'title' 		=> $value->title,
    			'image' 		=> $value->image,
    			'author' 		=> $value->author_name,
    			'category_id' 	=> $value->category_id,
    			'category' 		=> $value->category->name,
    			'created_at' 	=> $value->created_at->diffForHumans(),
    		];
    	}

    	$response = response()->json([
	        'status' => 'success',
	        'message' => 'News List',
	        'data' => [
	        	'banner' => $data_banner,
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
