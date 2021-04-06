<?php

declare(strict_types=1);

namespace App\Service\Timetable;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class AbstractCachingService
 * @package App\Service\Timetable
 * @author bernard-ng <ngandubernard@gmail.com>
 */
abstract class AbstractCachingService
{
    public const CACHE_PATH = '';
    protected string $root;
    protected Filesystem $fs;

    /**
     * ScrapingService constructor.
     * @param KernelInterface $kernel
     * @param Filesystem $fs
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(KernelInterface $kernel, Filesystem $fs)
    {
        $root = $kernel->getProjectDir() . static::CACHE_PATH;
        if (!file_exists($root)) {
            mkdir($root, 0777, true);
        }

        $this->root = $root;
        $this->fs = $fs;
        $this->fs = $fs;
    }
}
