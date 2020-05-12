<?php


namespace Dex\Microblog\Infrastructure\Persistence\Record;


use Phalcon\Mvc\Model;

class ReplyPostRecord extends Model
{
//    public string $id;
    public string $post_id;
    public ?string $original_post_id;

    public function initialize()
    {
        $this->setSchema('dbo');
        $this->setSource('reply');

        $this->belongsTo('original_post_id', PostRecord::class, 'id');
        $this->belongsTo('post_id', PostRecord::class, 'id');
    }

}
