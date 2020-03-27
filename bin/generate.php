<?php

require_once dirname(__DIR__).'/vendor/autoload.php';
$baseDir = dirname(__DIR__);

use eduVPN\Web\Markdown;
use fkooman\Template\Tpl;

$dateTime = new DateTime();
$postDir = sprintf('%s/posts', $baseDir);
$pageDir = sprintf('%s/pages', $baseDir);
$outputDir = sprintf('%s/output', $baseDir);
$blogOutputDir = sprintf('%s/blog', $outputDir);
$templateDir = sprintf('%s/views', $baseDir);

@mkdir($outputDir, 0755, true);
@mkdir($blogOutputDir, 0755, true);
@mkdir($outputDir.'/img', 0755, true);
@mkdir($outputDir.'/download', 0755, true);
@mkdir($outputDir.'/css', 0755, true);

$md = new Markdown();

$tpl = new Tpl([$templateDir]);
$tpl->addDefault(
    [
        'blogTitle' => 'Let\'s Connect!',
        'blogDescription' => 'Safe and Trusted',
        'blogAuthor' => 'Let\'s Connect!',
        'generatedOn' => $dateTime->format(DateTime::ATOM),
        'currentYear' => $dateTime->format('Y'),
    ]
);

$postsList = [];
$pagesList = [];

foreach (glob(sprintf('%s/*.md', $postDir)) as $postFile) {
    $postInfo = [];

    // obtain postInfo
    $f = fopen($postFile, 'r');
    $line = fgets($f);
    if (0 !== strpos($line, '---')) {
        throw new Exception(sprintf('invalid file! "%s"', $postFile));
    }
    $line = fgets($f);
    do {
        $xx = explode(':', $line, 2);
        $postInfo[trim($xx[0])] = trim($xx[1]);
        $line = fgets($f);
    } while (0 !== strpos($line, '---'));

    // read rest of the post
    $buffer = '';
    while (!feof($f)) {
        $buffer .= fgets($f);
    }

    fclose($f);
    $postOutputFile = basename($postFile, '.md').'.html';
    $blogPost = [
        'htmlContent' => $md->transform($buffer),
        'description' => isset($postInfo['description']) ? $postInfo['description'] : $postInfo['title'],
        'published' => $postInfo['published'],
        'publish' => isset($postInfo['publish']) ? 'no' !== $postInfo['publish'] : true,
        'title' => $postInfo['title'],
        'modified' => isset($postInfo['modified']) ? $postInfo['modified'] : null,
        'fileName' => $postOutputFile,
        'hide' => isset($postInfo['hide']) ? $postInfo['hide'] : 'no',
    ];

    $postsList[] = $blogPost;
}

usort($postsList, function ($a, $b) {
    return strtotime($a['published']) < strtotime($b['published']);
});

$postsYearList = [];
foreach ($postsList as $postInfo) {
    $postDate = new DateTime($postInfo['published']);
    $postYear = $postDate->format('Y');
    if ('yes' === $postInfo['hide']) {
        continue;
    }
    if (!array_key_exists($postYear, $postsYearList)) {
        $postsYearList[$postYear] = [];
    }
    $postsYearList[$postYear][] = $postInfo;
}

foreach (glob(sprintf('%s/*.md', $pageDir)) as $pageFile) {
    $pageInfo = [];

    $f = fopen($pageFile, 'r');
    $line = fgets($f);
    if (0 !== strpos($line, '---')) {
        throw new Exception(sprintf('invalid file "%s"!', $pageFile));
    }
    $line = fgets($f);
    do {
        $xx = explode(':', $line, 2);
        $pageInfo[trim($xx[0])] = trim($xx[1]);
        $line = fgets($f);
    } while (0 !== strpos($line, '---'));

    // read rest of the page
    $buffer = '';
    while (!feof($f)) {
        $buffer .= fgets($f);
    }

    fclose($f);
    $pageOutputFile = basename($pageFile, '.md').'.html';

    // find latest blog
    foreach ($postsList as $k => $postInfo) {
        if ('yes' === $postInfo['hide']) {
            continue;
        }
        $latestBlogIndex = $k;

        break;
    }

    $page = [
        'htmlContent' => $md->transform($buffer),
        'title' => $pageInfo['title'],
        'fileName' => $pageOutputFile,
        'priority' => isset($pageInfo['priority']) ? (int) $pageInfo['priority'] : 255,
        'latestBlog' => isset($pageInfo['latest_blog']) ? $postsList[$latestBlogIndex] : false,
        'hidePage' => isset($pageInfo['hide-page']) ? 'yes' === $pageInfo['hide-page'] : false,
    ];
    $pagesList[] = $page;
}

// add blog page
$pagesList[] = [
    'htmlContent' => $tpl->render(
        'index',
        [
            'postsYearList' => $postsYearList,
        ]
    ),
    'hidePage' => false,
    'title' => 'Blog',
    'requestRoot' => '../',
    'fileName' => 'blog/index.html',
    'priority' => 255,
    'latestBlog' => false,
];

// sort pages by priority
usort($pagesList, function ($a, $b) {
    if ($a['priority'] === $b['priority']) {
        return 0;
    }

    return ($a['priority'] < $b['priority']) ? -1 : 1;
});

foreach ($postsList as $post) {
    if ($post['publish']) {
        $postPage = $tpl->render(
            'post',
            [
                'requestRoot' => '../',
                'pagesList' => $pagesList,
                'activePage' => 'blog/index.html',
                'pageTitle' => $post['title'],
                'post' => $post,
                'latestBlog' => false,
            ]
        );
        file_put_contents($blogOutputDir.'/'.$post['fileName'], $postPage);
    }
}

foreach ($pagesList as $page) {
    $pagePage = $tpl->render(
        'page',
        [
            'requestRoot' => isset($page['requestRoot']) ? $page['requestRoot'] : '',
            'activePage' => $page['fileName'],
            'pagesList' => $pagesList,
            'pageTitle' => $page['title'],
            'pageContent' => $page,
            'latestBlog' => $page['latestBlog'],
        ]
    );
    file_put_contents($outputDir.'/'.$page['fileName'], $pagePage);
}

foreach (['img', 'download', 'css'] as $pathName) {
    foreach (glob($baseDir.'/'.$pathName.'/*') as $file) {
        copy($file, $outputDir.'/'.$pathName.'/'.basename($file));
    }
}
