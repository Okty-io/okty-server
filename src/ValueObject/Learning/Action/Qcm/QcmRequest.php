<?php

declare(strict_types=1);

namespace App\ValueObject\Learning\Action\Qcm;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class QcmRequest
{
    private array $questions;

    public function __construct(array $config)
    {
        $this->questions = [];
        foreach ($config as $question) {
            foreach ($question as $response) {
                if (!is_bool($response)) {
                    throw new \LogicException('Response should be a boolean value');
                }
            }

            $this->questions[] = $question;
        }
    }

    /**
     * @return mixed[]
     */
    public function getResponsesByQuestion(int $questionId): array
    {
        if (!isset($this->questions[$questionId])) {
            return [];
        }

        return array_keys(array_filter($this->questions[$questionId]));
    }
}
