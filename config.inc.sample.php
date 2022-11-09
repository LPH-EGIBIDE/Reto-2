<?php
require_once __DIR__ . '/Autoloader.php';
const WEB_APP_VERSION = "0.0.1-dev";


const DB_HOST = "localhost";
const DB_USER = "docker";
const DB_PASSWORD = "8b0un0unmind0n";
const DB_DATABASE = "lph_app2";

const APP_ROOT = __DIR__ . '/';

function getBuildInfo(): array
{
    $branch = getCurrentBranch();
    $commit = getCurrentCommitHash();
    $commitDate = getCurrentBranchDatetime($branch);

    return [
        'branch' => $branch,
        'commit' => $commit,
        'commitDate' => $commitDate,
        'buildName' => "WTFAQ v".WEB_APP_VERSION."-$branch-$commit ($commitDate)",
    ];
}

function getCurrentBranch(): string
{
    $data = file_get_contents(APP_ROOT.'.git/HEAD');
    $ar  = explode( "/", $data );
    $ar = array_reverse($ar);
    return  trim ("" . @$ar[0]) ;
}

function getCurrentBranchDatetime($branch='master' ): string
{
    $fname = sprintf( APP_ROOT.'.git/refs/heads/%s', $branch );
    $time = filemtime($fname);
    if($time != 0 ){
        return date("Y-m-d H:i:s", $time);
    }else{
        return  "time=0";
    }
}

function getCurrentCommitHash(): string
{
    $path = APP_ROOT.'.git/';

    $head = trim(substr(file_get_contents($path . "HEAD"), 4));

    return trim(substr(file_get_contents($path . $head), 0, 7));
}