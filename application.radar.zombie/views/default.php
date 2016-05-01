<!DOCTYPE html>
<html>
<head>
    <title>Default</title>
</head>
<body>
	
    <h1>Data</h1>
	
	<a href="/mikka">Read more...</a>
<?php if (isset($data)): ?>
    <pre><?php print_r($data); ?></pre>
<?php endif; ?>
</body>
</html>
