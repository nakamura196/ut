<?php
  $root = "./"; //適宜変更してください。
  $path = $_POST["path"];
  $dirname = dirname($path);
  if (!file_exists($dirname)) {
      mkdir($dirname, 0755, true);
  }
  move_uploaded_file($_FILES['media']['tmp_name'], $root.$path);
?>
