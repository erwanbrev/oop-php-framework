<?php

namespace App\Models;

class Tag extends Model
{

    protected $table = 'tags';

    /* l'inner join si dessous signifie : 
        post_tag avec alias 'pt' reliée avec ON ou pt.post_id = p.id */
    public function getPosts()
    {
        return $this->query("
            SELECT p.* FROM posts p
            INNER JOIN post_tag pt ON pt.post_id = p.id
            WHERE pt.tag_id = ?
        ", [$this->id]);
    }
}
