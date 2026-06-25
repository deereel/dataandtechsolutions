<?php
require_once 'config/config.php';
require_once 'config/database.php';

// Test cases
$testUrls = [
    "https://youtu.be/0tdlR1rBwkM",
    "https://www.youtube.com/watch?v=0tdlR1rBwkM",
    "https://www.youtube.com/watch?v=0tdlR1rBwkM&t=10s",
    "https://www.youoube.com/watch?v=excel-data-intro"
];

echo "<h2>YouTube URL Extraction Test</h2>";

foreach ($testUrls as $videoUrl) {
    echo "<div style='border: 1px solid #ddd; padding: 1rem; margin-bottom: 1rem;'>";
    echo "<p><strong>Input URL:</strong> " . $videoUrl . "</p>";
    
    $embedUrl = '';
    
    // Extract video ID
    if (strpos($videoUrl, '<iframe') !== false) {
        preg_match('/src="([^"]+)"/', $videoUrl, $matches);
        if (!empty($matches[1])) {
            $embedUrl = $matches[1];
        }
    } else {
        $videoUrl = strtolower($videoUrl);
        $videoUrl = str_replace('youoube', 'youtube', $videoUrl);
        
        if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
            preg_match('/v=([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
            if (!empty($matches[1])) {
                $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                if (strpos($videoUrl, '&') !== false) {
                    $params = substr($videoUrl, strpos($videoUrl, '&'));
                    $embedUrl .= $params;
                }
            }
        } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
            preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
            if (!empty($matches[1])) {
                $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                if (strpos($videoUrl, '?') !== false) {
                    $params = substr($videoUrl, strpos($videoUrl, '?'));
                    $embedUrl .= $params;
                }
            }
        } elseif (strpos($videoUrl, 'youtube.com/embed/') !== false) {
            $embedUrl = $videoUrl;
        } else {
            $embedUrl = $videoUrl;
        }
    }
    
    echo "<p><strong>Embed URL:</strong> " . $embedUrl . "</p>";
    
    // Extract video ID from embed URL
    if (strpos($embedUrl, 'youtube.com/embed/') !== false) {
        $idMatch = preg_match('/embed\/([a-zA-Z0-9_-]+)/', $embedUrl, $matches);
        if ($idMatch) {
            echo "<p style='color: green;'><strong>Video ID:</strong> " . $matches[1] . "</p>";
        }
    }
    
    echo "</div>";
}

// Check lesson 519
echo "<hr>";
echo "<h2>Checking Lesson 519</h2>";

try {
    $pdo = DatabaseConnection::getInstance();
    $stmt = $pdo->query('SELECT id, title, video_url FROM lessons WHERE id = 519');
    $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($lesson) {
        echo "<div style='border: 1px solid #ddd; padding: 1rem; margin-bottom: 1rem;'>";
        echo "<p><strong>ID:</strong> " . $lesson['id'] . "</p>";
        echo "<p><strong>Title:</strong> " . $lesson['title'] . "</p>";
        echo "<p><strong>Video URL:</strong> " . $lesson['video_url'] . "</p>";
        
        $embedUrl = '';
        
        if (strpos($lesson['video_url'], '<iframe') !== false) {
            preg_match('/src="([^"]+)"/', $lesson['video_url'], $matches);
            if (!empty($matches[1])) {
                $embedUrl = $matches[1];
            }
        } else {
            $videoUrl = strtolower($lesson['video_url']);
            $videoUrl = str_replace('youoube', 'youtube', $videoUrl);
            
            if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                preg_match('/v=([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
                if (!empty($matches[1])) {
                    $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                    if (strpos($videoUrl, '&') !== false) {
                        $params = substr($videoUrl, strpos($videoUrl, '&'));
                        $embedUrl .= $params;
                    }
                }
            } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
                if (!empty($matches[1])) {
                    $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                    if (strpos($videoUrl, '?') !== false) {
                        $params = substr($videoUrl, strpos($videoUrl, '?'));
                        $embedUrl .= $params;
                    }
                }
            } elseif (strpos($videoUrl, 'youtube.com/embed/') !== false) {
                $embedUrl = $videoUrl;
            } else {
                $embedUrl = $videoUrl;
            }
        }
        
        echo "<p><strong>Embed URL:</strong> " . $embedUrl . "</p>";
        
        if (strpos($embedUrl, 'youtube.com/embed/') !== false) {
            $idMatch = preg_match('/embed\/([a-zA-Z0-9_-]+)/', $embedUrl, $matches);
            if ($idMatch) {
                echo "<p style='color: green;'><strong>Video ID:</strong> " . $matches[1] . "</p>";
            }
        }
        
        echo "</div>";
    } else {
        echo "<p style='color: red;'>Lesson 519 not found</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>