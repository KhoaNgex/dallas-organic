<?php

class Blog
{

    use Model;

    protected $table = 'blogs';

    public function findTitle()
    {
        $query = "call getSomeBlogTitle();";
        return $this->query($query);
    }

    public function findAllTitle($offset)
    {
        $query = "call getAllBlogTitle($offset);";
        return $this->query($query);
    }
}