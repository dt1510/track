<?
$base_dir="/home/david/Desktop";

function rglob($pattern='*', $flags = 0, $path='')
{
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
    return $files;
}

function get_tracking_files() {
    global $base_dir;
    return rglob("meta.txt", 0, $base_dir);
}

function subject_name($meta_file) {
    $names = explode("/", $meta_file);
    return $names[count($names)-2];
}


function extract_list($meta_file) {
    $list = array();
    $content = file_get_contents($meta_file);
    $blocks = preg_split("/\n(\n)+/", $content);
    foreach($blocks as $block) {
        $sublist = preg_split("/\n/", $block);
        $heading = $sublist[0];
        unset($sublist[0]);
        $list[$heading]=$sublist;        
    }
    return $list;
}

function print_list($list) {

    foreach($list as $heading => $sublist) {
        echo "<div>";
        echo "<sublist_header>$heading</sublist_header>";
        echo "<ul>";
        foreach($sublist as $item) {
            if(preg_match("/https?/",$item)) {
                $item = "<a href='$item'>$item</a>";
            }
            echo "<li>$item</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}

function print_subject($file) {
    echo "<subject>".subject_name($file)."</subject>";
    print_list(extract_list($file));
}

?>

<html>
<head>
<link rel="stylesheet" href="reset.css" type="text/css">
</head>
<body>
<?
foreach(get_tracking_files() as $file) {
    print_subject($file);
}

?>

</body>
<style>
body{padding:3px;}
div{padding:3px;}
h1{font-weight:bold;}
sublist_header{font-weight:bold;}
subject{font-weight:bold;}
</style>
</html>
