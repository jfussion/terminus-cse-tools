<?php
namespace Pantheon\TerminusCseTools\Commands\Sweep;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;

class SweepSitesCommand extends SweepCommand
{
    /**
     * Leave client's site.
     *
     * @command sweep:sites
     * @alias sweep
     */
    public function sweepSite() {
      $output = $this->output();
      $user = $this->session()->getUser();

      $output->writeln('<info>Gathering sites...</>');
      $output->writeln('<info>======================================</>');
      $this->sites()->fetch(['team_only' => true]);

      // Get remove sites owned by me.
      $me = $user->id;
      $this->sites->filter(function($model) use ($me) {
        if ($model->get('owner') != $me) {
          return true;
        }
      });

      $sites = $this->sites->serialize();

      if (empty($sites)) {
        $this->log()->notice('No results');
      }
      else {
        // List all the sites.
        foreach($sites as $site) {
          $output->writeln($site['name']);
        }
        // Proceed with removing self to the team.
        if (!$this->confirm('Are you sure you want to remove yourself to these sites?')) {
          return;
        }
        foreach ($sites as $site_id => $value) {
          $output->write("Leaving " . $value['name'] . " site... ");
          $workflow = $this->getSite($site_id)->getUserMemberships()->get($me)->delete();
          $output->writeln("<info>Done!</>");
        }
        $this->log()->notice('Success!');
      }
    }
}
