<?php
namespace Portfolio\Domain\Gateway;

use Portfolio\Domain\Entity\Post as PostEntity;

interface Post
{
    public function getAllPosts();
    public function getPostById($id);
    public function savePost(PostEntity $post);
}
