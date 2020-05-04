<?php


namespace Dex\microblog\Core\Domain\Model\Record;


use Phalcon\Mvc\Model;

class PostRecord extends Model
{

    public string $id;
    public string $content;
    public string $post_id;
    public string $user_id;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function initialize()
    {
        $this->setSchema('dbo');
        $this->setSource('posts');

        $this->belongsTo('user_id', UserRecord::class, 'id');
        $this->hasMany('id', ReplyPostRecord::class, 'post_id');
    }

}
