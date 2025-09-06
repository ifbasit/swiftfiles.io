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

    private function formatPermissions($perms) {
        if (($perms & 0xC000) == 0xC000) {
            $info = 's'; // Socket
        } elseif (($perms & 0xA000) == 0xA000) {
            $info = 'l'; // Symbolic Link
        } elseif (($perms & 0x8000) == 0x8000) {
            $info = '-'; // Regular file
        } elseif (($perms & 0x6000) == 0x6000) {
            $info = 'b'; // Block special
        } elseif (($perms & 0x4000) == 0x4000) {
            $info = 'd'; // Directory
        } elseif (($perms & 0x2000) == 0x2000) {
            $info = 'c'; // Character special
        } elseif (($perms & 0x1000) == 0x1000) {
            $info = 'p'; // FIFO pipe
        } else {
            $info = 'u'; // Unknown
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                    (($perms & 0x0800) ? 's' : 'x' ) :
                    (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                    (($perms & 0x0400) ? 's' : 'x' ) :
                    (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                    (($perms & 0x0200) ? 't' : 'x' ) :
                    (($perms & 0x0200) ? 'T' : '-'));

        return $info;
        }


        public function getDirectoryContents($path = '') {
            if (!$path) {
                $path = '/'; 
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
                $isDir = is_dir($fullPath);

                // Defaults
                $size = null;
                $lastModified = null;
                $permissions = null;

                if (file_exists($fullPath) && is_readable($fullPath)) {
                    if (!$isDir) {
                        $size = @filesize($fullPath); // @ suppresses warnings
                    }
                    $lastModified = @date("Y-m-d H:i:s", filemtime($fullPath));
                    $perms = @fileperms($fullPath);
                    $permissions = $this->formatPermissions($perms);
                }

                $result[] = [
                    'name'        => $item,
                    'type'        => $isDir ? 'folder' : 'file',
                    'path'        => $fullPath,
                    'fileType'    => $isDir ? null : pathinfo($item, PATHINFO_EXTENSION),
                    'size'        => $size,
                    'lastModified'=> $lastModified,
                    'permissions' => $permissions
                ];
            }

            return $result;
        }


}
?>
