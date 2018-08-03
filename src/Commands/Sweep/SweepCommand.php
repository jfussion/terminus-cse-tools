<?php
namespace Pantheon\TerminusCseTools\Commands\Sweep;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;

/**
 * Class SweepCommand
 * @package Pantheon\TerminusCseTools\Commands
 */
abstract class SweepCommand extends TerminusCommand implements SiteAwareInterface
{
    use SiteAwareTrait;
}
