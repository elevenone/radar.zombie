<html>
<head>
    <title>My Site</title>




	<script src="/ui/js/jquery-1.9.1.js"></script>
	<script src="/ui/js/jquery.cookie.js"></script>
	<script src="/ui/js/jquery.pjax.js"></script>
	<script type="text/javascript">
		var direction = "right";
		$(document).ready(function(){

			$(document).pjax('a', '#main');

			$(document).on('pjax:start', function() {
				$(this).addClass('loading')
			});
			$(document).on('pjax:end', function() {
				$(this).removeClass('loading')
			});
		});
	</script>

</head>
<body>


<?php require 'partials/_header.php' ?>
<pre>

	<section id="main">
		<h1>PJAX using PHP</h1>

		<?php echo $this->getContent(); ?>

		<?php 
		$imhome = true;
		include("status.php");
		echo $what;
		?>

	</section>





</pre>
<?php require 'partials/_footer.php' ?>


</body>
</html>