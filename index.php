<?
$base_dirs="/home/david/Desktop;/var/www/track";

function rglob($pattern='*', $flags = 0, $path='')
{
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
    return $files;
}

function get_tracking_files() {
    global $base_dirs;
    $files = array();
    foreach(preg_split("/;/",$base_dirs) as $base_dir) {        
        $files=array_merge($files, rglob("todo.txt", 0, $base_dir));
    }
    return $files;
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

function print_subject_caption($file) {
    echo "<subject>".subject_name($file)."</subject><br/>$file";
}

function print_subject($file) {        
    print_subject_caption($file);
    print_list(extract_list($file));
}

function extract_priorities($list) {
    foreach($list as $heading => $sublist) {
        if(!preg_match("/^-p[0-9]/", $heading)) {
            unset($list[$heading]);
        }
    }
    return $list;
}

function print_subject_list() {
    foreach(get_tracking_files() as $file) {
        echo "<div>";        
        print_subject_priorities($file);
        echo "</div>";
    }    
}

function print_subject_priorities($file) {
    print_subject_caption($file);
    print_list(extract_priorities(extract_list($file)));
}

?>

<html>
<head>
<link rel="stylesheet" href="reset.css" type="text/css">
</head>
<body>
<?
print_subject_list();
?>

</body>
<style>
body{padding:3px;}
div{padding:3px;}
h1{font-weight:bold;}
sublist_header{font-weight:bold;}
subject{font-weight:bold;color:#f00;}
</style>
</html>
