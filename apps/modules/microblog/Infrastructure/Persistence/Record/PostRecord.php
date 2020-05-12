<?php


namespace Dex\Microblog\Infrastructure\Persistence\Record;


use Phalcon\Mvc\Model;

class PostRecord extends Model
{

    public string $id;
    public string $title;
    public string $content;
    public int $repost_counter;
    public int $share_counter;
    public int $reply_counter;
    public string $user_id;
    public ?string $created_at = "";
    public ?string $updated_at = "";
    public ?int $isReply = 0;

    public function initialize()
    {
        $this->setSchema('dbo');
        $this->setSource('posts');
        $this->useDynamicUpdate(true);

        $this->belongsTo('user_id', UserRecord::class, 'id');
        $this->hasMany('id', ReplyPostRecord::class, 'post_id');
    }

}
