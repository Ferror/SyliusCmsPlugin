<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCmsPlugin\Downloader;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;

final class ImageDownloader implements ImageDownloaderInterface
{
    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function download(string $url): File
    {
        $path = rtrim(sys_get_temp_dir(), \DIRECTORY_SEPARATOR) . \DIRECTORY_SEPARATOR . md5(random_bytes(10));
        $pathInfo = pathinfo($url);
        $extension = $pathInfo['extension'] ?? null;
        if (null !== $extension) {
            $path .= '.' . $extension;
        }
        $contents = file_get_contents($url);
        Assert::string($contents, sprintf('Content of file in path %s is null', $path));
        $this->filesystem->dumpFile($path, $contents);

        return new File($path);
    }
}
