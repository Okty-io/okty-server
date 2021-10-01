<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\History;
use App\ValueObject\Service\Args;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
interface HistoryContainerRepositoryInterface
{
    public function createFromArgs(History $history, Args $args);
}
