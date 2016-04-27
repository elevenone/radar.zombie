<hr/>
<?php
print_r($this->data);



if ( isset($this->data['posts'])  ) // && is_array($this->posts)
{
    // print_r($this->data['posts'] );
    // print_r($this->posts);
    foreach($this->data['posts'] as $post)
    {
        // print_r($posts);
        echo '<h2>' . htmlentities($post->getTitle()) . '</h2>';
        echo '<p>' . htmlentities($post->getExcerpt()) . '</p>';

        if ($post->hasId())
        {
            echo '<a href="/' . htmlentities($post->getId()) . '/">Read More</a>';
        }
        echo '<br/>';
    }

}
?>

<?php
/*
if ( isset($this->posts) && is_array($this->posts) )
{
    // echo '111111';
    // print_r($this->posts);
    foreach($this->posts as $post)
    {
        // print_r($posts);
        echo '<h2>' . htmlentities($post->getTitle()) . '</h2>';
        echo '<p>' . htmlentities($post->getExcerpt()) . '</p>';

        if ($post->hasId())
        {
            echo '<a href="/' . htmlentities($post->getId()) . '/">Read More</a>';
        }
        echo '<br/>';
    }

}
*/
?>
<hr/>