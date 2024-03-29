<?php
/**
 * ClassName: Article
 * 文章模型
 * @author      David<guochaowan2008@gmail.com>
 * @version     v1.1.0
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Article extends Model
{
    /**
     * 模型表article
     * @var string
     */
    protected $table = "article";

    protected $fillable = array(
        'title',
        'cid',
        'description',
        'content',
        'img',
        'is_publish',
        'is_delete',
        'sort'
    );

    /**
     * 关联文章分类模型
     * @access public
     * @return $this
     */
    public function category(){
        return $this->belongsTo(ArticleCategory::class, 'cid');
    }
    
}
