<?php

/**
 * Recursively delete specified files in the current directory and subdirectories.
 *
 * @param string $dir The directory to search in.
 * @param array $filesToDelete The filenames to delete.
 */
function deleteFilesRecursively($dir, $filesToDelete) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    $logFile = __DIR__ . '/delete_files_log.txt';
    file_put_contents($logFile, "Starting file deletion process...\n", FILE_APPEND);

    foreach ($iterator as $fileInfo) {
        if (in_array($fileInfo->getFilename(), $filesToDelete)) {
            if (unlink($fileInfo->getPathname())) {
                file_put_contents($logFile, "Deleted: " . $fileInfo->getPathname() . "\n", FILE_APPEND);
            } else {
                file_put_contents($logFile, "Failed to delete: " . $fileInfo->getPathname() . "\n", FILE_APPEND);
            }
        }
    }

    file_put_contents($logFile, "File deletion process completed.\n", FILE_APPEND);
}

$filesToDelete = ['.htaccess'];
deleteFilesRecursively(__DIR__, $filesToDelete);

?>