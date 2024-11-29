<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = $_POST['link'] ?? '';
    $fileName = $_POST['fileName'] ?? '';

    if (empty($link) || empty($fileName)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit;
    }

    // Sanitize file name
    $fileName = preg_replace('/[^a-zA-Z0-9-_]/', '', $fileName);
    $filePath = "hls/{$fileName}.m3u8";

    // M3U8 content
    $m3u8Content = "#EXTM3U
#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=2048000,RESOLUTION=1280x720
#EXT-X-VERSION:3
#EXT-X-MEDIA-SEQUENCE:0
#EXT-X-ALLOW-CACHE:YES
#EXT-X-TARGETDURATION:11
#EXTINF:4, no desc
{$link}";

    // Save the file
    if (!file_put_contents($filePath, $m3u8Content)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create file.']);
        exit;
    }

    $url = "http://{$_SERVER['HTTP_HOST']}/hls/{$fileName}.m3u8";
    echo json_encode(['success' => true, 'url' => $url]);
}
