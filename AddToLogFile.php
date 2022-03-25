<?php

function AddtoLogFile($statusOutput, $postfix)
{
    $logFolder = "./logs/";
    $file = $logFolder . date("Y-m-d"). '_' . $postfix . '.log';
    $statusOutput = ReplaceHTMLLineBreaks($statusOutput);
    $seperator = "-----" . date("Y-m-d H:i") . "---------------\n";
    $input = $seperator . $statusOutput . "\n\n";
    file_put_contents($file, $input, FILE_APPEND);

    $sqlFiles = glob($logFolder . "*.{log}",GLOB_BRACE);
    CleanupFiles($sqlFiles, 7);
}

function ReplaceHTMLLineBreaks($statusOutput)
{
    $search = array("<p>", "</p>", "<br>");
    $replace = array("", "\n\n", "\n");
    $output = str_replace($search, $replace, $statusOutput);
    return $output;
}