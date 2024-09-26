<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Messenger;

use Doctrine\ORM\EntityManagerInterface;
use Homebot\Contracts\StateRepositoryInterface;
use Homebot\Entity\State;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class StateHandler
{
    public function __construct(
        private StateRepositoryInterface $repository,
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(StateMessage $message): void
    {
        $repository = $this->repository;

        $last = $repository->findLast($message->deviceId, $message->unit);

        $state = new State();
        $state->deviceId = $message->deviceId;
        $state->state = $message->state;
        $state->unit = $message->unit;
        $state->timestamp = $message->timestamp;

        if (!\is_null($last)) {
            $state->lastState = $last->state;
        }

        $this->em->persist($state);
        $this->em->flush();
    }
}
