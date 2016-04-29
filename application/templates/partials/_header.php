header
<br/>
<?php require '_navigation.php' ?>


<?php
// begin buffering output for a named section
$this->beginSection('local-nav');

echo "<div>";
echo "local nav";
echo "</div>";

// end buffering and capture the output
$this->endSection();
?>