<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=document_name.doc");

echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";
echo "<b>My first document</b>";
echo "</body>";
echo "</html>";
?>
<form method="POST" action="word.php">

<h1>Form</h1>

<input placeholder="Heading" name="heading" class="text-field" type="text" required="">

<button type="submit" name="word">Doc</button>

</form>