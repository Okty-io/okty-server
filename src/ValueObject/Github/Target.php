<?php

declare(strict_types=1);

namespace App\ValueObject\Github;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Target
{
    private string $branch;
    private string $message;
    private string $folder;

    public function __construct(string $branch, string $message, string $folder)
    {
        if (empty($branch)) {
            throw new \InvalidArgumentException('Branch name is require');
        }

        if ('dev' == $branch) {
            throw new \InvalidArgumentException('Specified branch name is not allowed');
        }
        $this->branch = $branch.'-'.substr(uniqid(), -3);

        if (empty($message)) {
            throw new \InvalidArgumentException('Commit message is require');
        }
        $this->message = $message;

        if (empty($folder)) {
            throw new \InvalidArgumentException('Folder is require');
        }
        $this->folder = trim($folder, '/').'/';
    }

    public function getCommitMessage(): string
    {
        return $this->message;
    }

    public function getFolder(): string
    {
        return $this->folder;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }
}
