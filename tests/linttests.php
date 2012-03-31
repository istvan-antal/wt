<?php
chdir('..');

$method = 'lint';
if (isset($argv[1])) {
    $method = $argv[1];
}

$passed = 0;
$failed = 0;

if ($handle = opendir('tests/good/')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." && substr($file, -3) === '.js') {
            //echo "$file\n";
            unset($output);
            exec("wt $method tests/good/$file", $output, $status);
            if ($status !== 0) {
                echo "$file fail:\n\n";
                foreach ($output as $out) {
                    echo $out."\n";
                }
                $failed += 1;
            } else {
                $passed += 1;
            }
        }
    }
    closedir($handle);
}

if ($handle = opendir('tests/bad/')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." && substr($file, -3) === '.js') {
            //echo "$file\n";
            unset($output);
            exec("wt $method tests/bad/$file", $output, $status);
            if ($status === 0) {
                echo "$file fail:\n\n";
                foreach ($output as $out) {
                    echo $out."\n";
                }
                $failed += 1;
            } else {
                $passed += 1;
            }
        }
    }
    closedir($handle);
}

echo "\n\n";
echo "Total: ".($passed + $failed)." Passed: $passed Failed: $failed\n";