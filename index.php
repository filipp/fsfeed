<?php
////
// fsfeed/index.php
// manage directory feeds
$msg = 'fsfeed 0.1';
$db = new PDO('sqlite:fsfeed.db');
if (isset($_POST['delete'])) {
  $stmt = $db->prepare('DELETE FROM path WHERE id = ?');
  $stmt->execute(array($_POST['delete']));
  $msg = 'Path deleted';
}
if (isset($_POST['add'])) {
  if (!realpath($_POST['path']) || empty($_POST['path'])) {
    $msg = "Invalid path: {$_POST['path']}";
  } else {
    $stmt = $db->prepare('INSERT INTO path (path) VALUES (?)');
    $stmt->execute(array($_POST['path']));
    $msg = 'Path added';
  }
}
$rows = $db->query('SELECT * FROM path')->fetchAll();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>fsfeed</title>
	<style type="text/css" media="screen">
    body, input, button {
      font: 1.2em "Lucida Grande", "Trebuchet MS", Verdana, sans-serif;
    }
    table {
      width:80%;
      margin: 200px auto;
    }
    input[type="text"] {
      width: 80%;
    }
    td, th {
      padding:5px;
      text-align:left;
    }
    th {
      background:#444;
      color:#fff;
    }
	</style>
</head>

<body>
  <form method="post" action="#">
  <table>
    <thead>
      <tr>
        <th colspan="2"><?php echo $msg ?></th>
      </tr>
    </thead>
<?php foreach ($rows as $r): ?>
    <tr>
      <td><a href="feed.php?f=<?php echo $r['id'] ?>" target="_blank"><?php echo $r['path'] ?></a></td>
      <td style="width:40px"><button name="delete" value="<?php echo $r['id'] ?>"/>Delete</td>
    </tr>
<?php endforeach ?>
    <tr>
      <td><input type="text" name="path"/></td>
      <td><input type="submit" name="add" value="Add Path"/></td>
    </tr>
  </table>
  </form>
</body>
</html>
