<?php

namespace App\Libraries\Base\Utilities;

class Version
{
    protected $major;

    protected $minor;

    protected $path;

    protected bool $dev = false;

    public function __construct(?string $version = null)
    {
        if (! is_null($version)) {
            $this->setVersion($version);
        }
    }

    public function setVersion(string $version): void
    {
        if (strpos($version, "-dev") !== false) {
            $this->dev = true;
            $version = str_replace("-dev", "", $version);
        }
        $version = explode('.', $version);
        $this->major = $version[0] ?? 1;
        $this->minor = $version[1] ?? null;
        $this->path = $version[2] ?? null;
    }

    public function isDev(): bool
    {
        return $this->dev;
    }

    public function lessThan(Version $required): bool
    {
        return $this->compare($this, $required) === -1;
    }

    /**
     * @param Version $v1
     * @param Version $v2
     *
     * @return int
     */
    public function compare(Version $v1, Version $v2)
    {
        $major = $this->comparePart($v1->major, $v2->major);
        if ($major != 0) {
            return $major;
        }

        $minor = $this->comparePart($v1->minor, $v2->minor);
        if ($minor != 0) {
            return $minor;
        }

        return $this->comparePart($v1->path, $v2->path);
    }

    protected function comparePart(?int $part1, ?int $part2): int
    {
        if ($part1 > $part2) {
            return 1;
        }
        if ($part1 === $part2) {
            return 0;
        }

        return -1;
    }

    public function lessThanOrEqualTo(Version $required): bool
    {
        return $this->compare($this, $required) < 1;
    }

    public function equal(Version $required): bool
    {
        return $this->compare($this, $required) === 0;
    }

    public function greaterThan(Version $required): bool
    {
        return $this->compare($this, $required) === 1;
    }

    public function greaterThanOrEqualTo(Version $required): bool
    {
        return $this->compare($this, $required) > -1;
    }
}
