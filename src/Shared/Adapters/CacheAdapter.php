<?php

declare(strict_types=1);

namespace App\Shared\Adapters;

final class CacheAdapter
{
    /**
     * @var string
     */
    private string $cachePath;

    /**
     * CacheAdapter constructor.
     *
     * @param string $cachePath
     */
    public function __construct(string $cachePath)
    {
        $this->cachePath = $cachePath;
    }

    /**
     * Get the cache path.
     *
     * @return string
     */
    public function getCachePath(): string
    {
        return $this->cachePath;
    }

    /**
     * Get the cache content.
     *
     * @return string
     */
    public function getCacheContent(): string
    {
        return file_get_contents($this->cachePath);
    }

    /**
     * Set the cache content.
     *
     * @param string $content
     */
    public function setCacheContent(string $content): void
    {
        file_put_contents($this->cachePath, $content);
    }
}