<?php
namespace Application\Domain\Gateway;

// use Portfolio\Domain\Entity\Post as PostEntity;

interface Post
{
    // public function getAllPosts();
    public function getPageById($id);
    // public function savePost(PostEntity $post);
}
