<?php

namespace App;


use \App\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Yaml\Tests\B;

class Post extends Model
{

    use Searchable;



    /*
     * 搜索的type
     */
    public function searchableAs()
    {
        return 'post';
    }

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }


    // 关联用户
        public  function user()
        {
            return $this->belongsTo('App\User','user_id','id');

         }

         public  function  comments()
         {
             return $this->hasMany('App\Comment')->orderBy('created_at','desc');
         }

         // 和用户进行关联
    public  function  zan($user_id)
    {

        return $this->hasOne(\App\Zan::class)->where('user_id',$user_id);
    }

    //文章所有赞
    public  function  zans()
    {
        return $this->hasMany(\App\Zan::class);
    }
    // 属于某个作者的文章
    public  function  scopeAuthorBy(Builder $query,$user_id)
    {
        return $query->where('user_id',$user_id);
    }

    public  function  postTopics()
    {
        return $this->hasMany(\App\PostTopic::class,'post_id','id');
    }

    // 不属于某个标题的文章
    public  function scopeTopicNotBy(Builder $query,$topic_id)
    {
        return $query->doesntHave('postTopics','and',function ($q)use($topic_id){
            $q->where('topic_id',$topic_id);
        });
    }
}
