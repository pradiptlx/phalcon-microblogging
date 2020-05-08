<?php


namespace Dex\Microblog\Infrastructure\Persistence\Record;


use Phalcon\Mvc\Model;

class ReplyPostRecord extends Model
{
    public string $id;
    public string $content;
    public string $post_id;
//    public string $user_id;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function initialize()
    {
        $this->setSchema('dbo');
        $this->setSource('reply');

        $this->belongsTo('post_id', PostRecord::class, 'id');
//        $this->belongsTo('user_id', UserRecord::class, 'id');
    }

    public function beforeSave()
    {
        $this->created_at = (new \DateTime())->format('Y-m-d H:i:s');
    }

    public function beforeUpdate()
    {
        $this->updated_at = (new \DateTime())->format('Y-m-d H:i:s');
    }

}
