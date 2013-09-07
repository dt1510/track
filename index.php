<?
$base_dir="/home/david/Desktop";

function rglob($pattern='*', $flags = 0, $path='')
{
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
    return $files;
}
?>

<html>
<body>
<h1>Personal management tracking</h1>
<?
var_export(rglob("todo.txt", 0, $base_dir));

?>

</body>
</html>
