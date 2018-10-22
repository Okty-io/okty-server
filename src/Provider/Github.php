<?php declare(strict_types=1);

namespace App\Provider;

use Github\Api\Repo;
use Github\Client;
use Github\Exception\RuntimeException;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Github
{
    private $client;
    private $githubUser;
    private $githubRepo;
    private $githubBranch;
    private $cacheItemPool;

    public function __construct(
        Client $client,
        string $githubUser,
        string $githubRepo,
        string $githubBranch,
        CacheItemPoolInterface $cacheItemPool
    )
    {
        $this->client = $client;
        $this->githubUser = $githubUser;
        $this->githubRepo = $githubRepo;
        $this->githubBranch = $githubBranch;
        $this->cacheItemPool = $cacheItemPool;

        $this->client->addCache($cacheItemPool);
    }

    private function getRepo(): Repo
    {
        /** @var Repo $repo */
        $repo = $this->client->api('repo');

        return $repo;
    }

    public function getFile(string $path): string
    {
        try {
            return $this->getRepo()->contents()->download($this->githubUser, $this->githubRepo, $path, $this->githubBranch);
        } catch (\Exception $e) {
            throw new FileNotFoundException($path);
        }
    }

    public function getTree(string $path): array
    {
        try {
            return $this->getRepo()->contents()->show($this->githubUser, $this->githubRepo, $path, $this->githubBranch);
        } catch (RuntimeException $e) {
            throw new FileNotFoundException($path);
        }
    }
}
