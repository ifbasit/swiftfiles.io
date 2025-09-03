<?php
class TreeNode {

    /**
     * Find the topmost accessible directory starting from a given path.
     */
    private function getRootPath($path) {
        $current = realpath($path);

        while ($current !== false) {
            $parent = dirname($current);

            if ($parent === $current) break; // reached filesystem root
            if (!is_readable($parent)) break; // last accessible

            $current = $parent;
        }

        return $current ?: realpath($path);
    }

    /**
     * Get top-level contents of a directory, including full paths.
     */
    public function getDirectoryContents($path = '') {
        // If no path sent, default to server root (top-most accessible)
        if (!$path) {
            $path = '/'; // or use your root folder
        }

        $realPath = realpath($path);
        if ($realPath === false || !is_readable($realPath)) {
            return ['error' => "Cannot read directory: $path"];
        }

        $items = scandir($realPath);
        $result = [];

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $fullPath = $realPath . DIRECTORY_SEPARATOR . $item;

            if (is_dir($fullPath)) {
                $result[] = [
                    'name' => $item,
                    'type' => 'folder',
                    'path' => $fullPath // send absolute path for lazy loading
                ];
            } else {
                $result[] = [
                    'name' => $item,
                    'type' => 'file',
                    'path' => $fullPath,
                    'fileType' => pathinfo($item, PATHINFO_EXTENSION)
                ];
            }
        }

        return $result;
    }
}
?>
