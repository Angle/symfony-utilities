<?php

namespace Angle\SfUtilities;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;

abstract class KillableCommand extends Command implements SignalableCommandInterface
{
    public function getSubscribedSignals(): array
    {
        return [
            SIGINT, // Ctrl+C
            SIGTERM, // Ctrl+E
            // SIGKILL, // Ctrl+K, this is a special signal that can't be ignored and we cannot change its behaviour
        ];
    }

    public function handleSignal(int $signal): void
    {
        if (SIGINT === $signal) {
            echo "Interruption [SIGINT] received." . PHP_EOL;
        } elseif (SIGTERM === $signal) {
            echo "Termination [SIGTERM] received." . PHP_EOL;
        }

        exit;
    }
}