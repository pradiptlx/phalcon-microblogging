<?php


namespace Dex\microblog\Infrastructure\Persistence\Record;


use Phalcon\Mvc\Model;
use Ramsey\Uuid\Uuid;

class FileManagerRecord extends Model
{
    public string $id;
    public string $file_name;
    public string $path;
    public string $post_id;

    public function initialize()
    {
        $this->setSchema('dbo');
        $this->setSource('files_manager');

        $this->belongsTo('post_id', PostRecord::class, 'id');
    }

    public function onConstruct()
    {
        $this->id = Uuid::uuid4()->toString();
    }
}
