<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}
include './core/TreeNode.class.php';
include './core/User.class.php';

$tree_node = new TreeNode();
$user = new User();
$request = $_GET['request'] ?? null;

if ($request === 'tree') {
    $path = $_GET['path'] ?? __DIR__; // default = server root
    $items = $tree_node->getDirectoryContents($path);

    // Sort: folders first, then files; each alphabetically
    usort($items, function($a, $b) {
        if ($a['type'] === $b['type']) {
            return strcasecmp($a['name'], $b['name']); // alphabetical
        }
        return ($a['type'] === 'folder') ? -1 : 1; // folders first
    });

    echo json_encode($items, JSON_PRETTY_PRINT);
    exit;
}

if($request == 'user'){
    $user = $user->get();
    echo json_encode($user, JSON_PRETTY_PRINT);
    exit;
}


?>

