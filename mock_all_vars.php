<?php
$directory = '/home/ikm/Bureau/Dossier_HTDOCS/lareuissite/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$regex = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$globalsToIgnore = ['_SESSION', '_POST', '_GET', '_SERVER', '_COOKIE', '_FILES', '_REQUEST', 'errors', 'message', 'loop', '__env', 'app', 'component', 'slot'];

foreach ($regex as $file) {
    $filePath = $file[0];
    $content = file_get_contents($filePath);
    
    // Find all variables
    preg_match_all('/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', $content, $matches);
    $variables = array_unique($matches[1]);
    
    $mockLines = [];
    foreach ($variables as $var) {
        if (in_array($var, $globalsToIgnore)) continue;
        
        // Skip if it's a foreach loop variable. e.g., foreach ($items as $var)
        if (preg_match('/foreach\s*\(\s*\$[^ ]+\s+as\s+\$'.$var.'\s*\)/i', $content)) continue;
        if (preg_match('/foreach\s*\(\s*\$[^ ]+\s+as\s+\$[^ ]+\s*=>\s*\$'.$var.'\s*\)/i', $content)) continue;
        
        // Skip if it's already checked in the file or assigned
        // Actually, prepending if(!isset($var)) is safe even if assigned later.
        
        // Guess type based on usage: if used in foreach($var as ...), it should be []
        $isLoop = preg_match('/foreach\s*\(\s*\$'.$var.'\s+as/i', $content) || preg_match('/count\(\$'.$var.'\)/i', $content);
        $defaultValue = $isLoop ? '[]' : "''";
        
        // Check if we already have this mock in the file
        if (!str_contains($content, "if (!isset(\$$var))")) {
            $mockLines[] = "if (!isset(\$$var)) \$$var = $defaultValue;";
        }
    }
    
    if (!empty($mockLines)) {
        $mockBlock = "<?php\n" . implode("\n", $mockLines) . "\n?>\n";
        
        // Insert right after @extends or at the very top
        if (preg_match('/^(@extends\([^)]+\)\s*)/m', $content, $m, PREG_OFFSET_CAPTURE)) {
            $pos = $m[0][1] + strlen($m[0][0]);
            $content = substr_replace($content, $mockBlock, $pos, 0);
        } else {
            $content = $mockBlock . $content;
        }
        
        file_put_contents($filePath, $content);
        echo "Mocked variables in $filePath\n";
    }
}
echo "Done.\n";
