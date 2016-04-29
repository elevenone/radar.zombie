ok
<pre>
<?php


require dirname(__DIR__) . '/application/Config/AuraViews.php';


$views = new \Application\Config\AuraViews;
// $zzz = $views->getViews();

$template_path = realpath( __DIR__ . '/../application/templates');
$staticpages_path = $template_path . '/staticpages';
$partials_path = $template_path . '/partials';

$zzz = [
    'views' => [

        // paths
        'path' => $template_path,
        'staticpages_path' => $staticpages_path,
        'partials_path' => $partials_path,

        'layout' => '/layout.php',

        'partials' => [
            'content' => '/_content.php',
        ]
    ]
];










print_r($zzz);
// var_dump($zzz);
echo '<hr/>';



// template root path
$template_path = $zzz['views']['path'];

// layout view path
$layout = $template_path . $zzz['views']['layout'];

// staticpages view path
$staticpages =  $zzz['views']['staticpages_path'];
// print_r( $this->staticpages );

// partials path
$partials_path = $zzz['views']['partials_path'];


echo '<hr/>';

print_r($template_path);

echo '<hr/>';


echo '<hr/>';

print_r($layout);

echo '<hr/>';

echo '<hr/>';

print_r($staticpages);

echo '<hr/>';

echo '<hr/>';

print_r($partials_path);

echo '<hr/>';





?>
</pre>
ok