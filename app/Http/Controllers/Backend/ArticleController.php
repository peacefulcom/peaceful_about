<?php
/**
 * ClassName: ArticleController
 * 文章分类控制器
 * @author      David<guochaowan2008@gmail.com>
 * @version     v1.1.0
 */
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\Admin;
use App\Models\Article;
use App\Models\ArticleCategory;

use App\Library\ImageUpload;

class ArticleController extends Controller
{  
    private $prefixName = 'article';

    public function __construct()
    {  
         
    }

    /**
     * 文章列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $articleModel = new Article();
        $query = $articleModel->query();
        $query->where('is_delete', '=', 0);
        $articles = $query->orderBy('id', 'asc')->paginate(20);
        return view('backend.article.index')->with('articles', $articles);
    }

    /**
     * 创建文章
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request, ImageUpload $uploader)
    {
        if($request->has('submit')) {
            $validator = $request->validate( [
                'title' => 'required|string|max:25',
                'sort' => 'integer|max:99',
                'cid' => 'integer|max:99',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if(!$validator) {
                return Redirect::back()->withErrors();
            }
            //$fileName = $this->prefixName.'_'.time().'.'.$request->image->extension();
            //$request->image->move(public_path('images'), $fileName);
            $fileName = $uploader->save($request->image, $this->prefixName, 'n');
            if (!$fileName) {
                return Redirect::back()->withErrors(['message' => 'Failed to upload an image.']);
            }
            $id = Article::create([
                'title' => $request->get('title'),
                'cid' => $request->get('cid'),
                'description' => $request->get('description'),
                'img' => $fileName,
                'content' => $request->get('content'),
                'is_publish' => $request->get('is_publish') == 'on' ? 1 : 0,
                'sort' => $request->get('sort'),
            ])->id;
            if($id) {
                return Redirect::route('article')->with(['message' => 'Created a new article successfuly.']);
            } else {
                return Redirect::back()->withErrors(['message' => 'Failed to created a new article .']);
            }
        }
        $articleCategoryModel = new ArticleCategory();
        $category = $articleCategoryModel->getArticleCategorySelection();
        return view('backend.article.create')->with('category', $category);
    }

    /**
     * 修改文章
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request, ImageUpload $uploader, $id)
    {
        $article = Article::find($id);
        if(!$article) {
            return Redirect::back()->with('message', 'This article does not exist.');
        }
        if($request->has('submit')) {
            $validator = $request->validate( [
                'title' => 'required|string|max:25',
                'sort' => 'integer|max:99',
            ]);
            if(!$validator) {
                return Redirect::back()->withErrors();
            }
            if ($request->image) {
                //$fileName = $this->prefixName.'_'.time().'.'.$request->image->extension();
                //$request->image->move(public_path('images'), $fileName);
                $fileName = $uploader->save($request->image, $this->prefixName, 'n');
                if (!$fileName) {
                    return Redirect::back()->withErrors(['message' => 'Failed to upload an image.']);
                }
            }
            $article->title = $request->get('title');
            $article->cid = $request->get('cid');
            $article->img = $request->image ? $fileName : $article->img;
            $article->description = $request->get('description');
            $article->content = $request->get('content');
            $article->is_publish = $request->get('is_publish') == 'on' ? 1 : 0;
            $article->sort = $request->get('sort');
           if(!$article->save()){
                return Redirect::back()->withErrors(['message' => 'Failed to upate this article.']);
           }   
           return Redirect::back()->with(['message' => 'Update article successfuly.']);
        }
        $articleCategoryModel = new ArticleCategory();
        $category = $articleCategoryModel->getArticleCategorySelection();
        return view('backend.article.edit',compact('article'))->with('category', $category);
    }

    /**
     * 删除文章
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request, $id)
    {
        $article = Article::find($id);
        if(!$article) {
            return Redirect::back()->with('message', 'This article does not exist.');
        }

        $article->is_delete = 1;
        $article->deleted_at = date('Y-m-d H:i:s', time());

        if(!$article->save()){
            return Redirect::back()->withErrors(['message' => 'Failed to delete this article.']);
        }   
        return Redirect::back()->with(['message' => 'Delete article successfuly.']);
    }

}