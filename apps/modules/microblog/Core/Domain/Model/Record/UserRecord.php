<?php


namespace Dex\microblog\Core\Domain\Model\Record;


use Phalcon\Mvc\Model;

class UserRecord extends Model
{

    public string $id;
    public string $username;
    public string $fullname;
    public string $email;
    public string $password;
    public string $role_id;
    public string $created_at;
    public string $updated_at;

    public function initialize(){
        $this->setConnectionService('db');
        $this->setSource('user');

        $this->setSchema('dbo');
        $this->setSource('users');

//        $this->belongsTo('role_id', Role::class, 'id');

        $this->hasMany('id', PostRecord::class, 'user_id');
        $this->hasMany('id', ReplyPostRecord::class, 'user_id');
    }

}
