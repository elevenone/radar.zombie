<html>
<head>
    <title>My Site</title>
</head>
<body>

<form method="post">
    <p><input type="text" name="title" placeholder="Title" size="100"></p>
    <p><textarea name="content" placeholder="Content" cols="100" rows="10"></textarea></p>
    <p><textarea name="excerpt" placeholder="Excerpt (optional)" cols="100" rows="2"></textarea></p>
    <p><button type="submit">Save Post</button>
</form>

<pre>
<?php
   // print_r($this->data);

?>



<?php require '_' . $this->partial ?>



<?php // require '_content.php' ?>


</pre>
</body>
</html>