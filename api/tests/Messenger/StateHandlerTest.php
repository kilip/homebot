<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Tests\Messenger;

use Doctrine\ORM\EntityManagerInterface;
use Homebot\Contracts\StateRepositoryInterface;
use Homebot\Entity\State;
use Homebot\Messenger\StateHandler;
use Homebot\Messenger\StateMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @covers StateHandler
 * @covers StateMessage
 * @covers State
 */
class StateHandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $repo = $this->createMock(StateRepositoryInterface::class);
        $state = new StateMessage(
            $id = Uuid::v1(),
            '250',
            $unit = 'voltage',
            new \DateTimeImmutable($ts = '2024-04-11T23:43:58')
        );
        $em = $this->createMock(EntityManagerInterface::class);
        $handler = new StateHandler($repo, $em);
        $last = $this->createMock(State::class);
        $last->state = '252';

        $repo->expects($this->once())
            ->method('findLast')
            ->with($id, $unit)
            ->willReturn($last);

        $em->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(State::class));

        $handler($state);
    }
}
