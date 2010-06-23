<?php
////
// fsfeed/feed.php
// turn a path into an Atom feed
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 'On');
$db = new PDO('sqlite:fsfeed.db');
$stmt = $db->prepare('SELECT * FROM path WHERE id = ?');
$stmt->execute(array($_GET['f']));
$rows = current($stmt->fetchAll());
$path = $rows['path'];
header('Content-Type: application/atom+xml');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title>fsfeed - <?php echo $path ?></title> 
  <updated><?php echo strftime('%Y-%m-%dT%TZ') ?></updated>
  <author> 
    <name>fsfeed 0.1</name>
  </author> 
  <id>urn:uuid:<?php echo sha1($path) ?></id>
<?php
    
  foreach(glob("{$path}/*", GLOB_NOSORT) as $p):
    
    $uuid = sha1($p.filemtime($p));
    $ts = strftime('%Y-%m-%dT%TZ', filemtime($p));
    $created = strftime('%c', filectime($p));
    $updated = strftime('%c', filemtime($p));
    $size = round(filesize($p)/pow(1024, 2), 2);
    $summary = sprintf("Created: %s\nUpdated: %s\nSize: %s MB", $created, $updated, $size);
    $link = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/get.php?f='.urlencode($p);
      
?>
  <entry>
    <title><?php echo basename($p) ?></title>
    <id>urn:uuid:<?php echo $uuid ?></id>
    <link href="http://<?php echo $link ?>"/>
    <updated><?php echo $ts ?></updated>
    <summary><?php echo $summary ?></summary>
  </entry>
<?php endforeach ?>
</feed>
