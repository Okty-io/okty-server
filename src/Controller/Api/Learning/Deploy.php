<?php

declare(strict_types=1);

namespace App\Controller\Api\Learning;

use App\Service\Learning\Import as LearningImport;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Deploy
{
    private LearningImport $learningImport;

    public function __construct(
        LearningImport $learningImport
    ) {
        $this->learningImport = $learningImport;
    }

    /**
     * @Route("learning/deploy", methods={"POST"})
     */
    public function handle(): Response
    {
        $this->learningImport->import();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
