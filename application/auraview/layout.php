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



<pre>



<?php require 'partials/_header.php' ?>



<section id="main">



        <?php echo $this->getContent(); ?>



</section>



<?php require 'partials/_footer.php' ?>



</pre>



</body>
</html>