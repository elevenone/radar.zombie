ok
<pre>
<?php


require dirname(__DIR__) . '/application/Config/AuraViews.php';


$views = new \Application\Config\AuraViews;
// $zzz = $views->getViews();

$zzz = [
    'views' => [
        'path' => realpath( __DIR__ . '/../application/auraview'),
        'layout' => '/layout.php',
        'error' => '/_error.php',
        'partials' => [
            'content' => '/_content.php',
            'header' => '/_header.php',
            'footer' => '/_footer.php',
            ]
        ]
    ];






print_r($zzz);
// var_dump($zzz);
echo '<hr/>';

print_r($zzz['views']);
// var_dump($zzz['views']);
echo '<hr/>';

print_r($zzz['views']['path']);

echo '<hr/>';






?>
</pre>
ok