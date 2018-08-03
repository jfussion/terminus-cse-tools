<?php
namespace Pantheon\TerminusCseTools\Commands\Sweep;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;

class FrozenSitesCommand extends SweepCommand
{
  /**
   * Sweep/Delete frozen sites.
   *
   * @command sweep:frozen
   */
  public function index() {
    $output = $this->output();


    $user = $this->session()->getUser();

    $output->writeln('<info>Gathering sites...</>');
    $output->writeln('<info>======================================</>');
    $this->sites()->fetch(['team_only' => true]);

    // Get Sites that I own.
    $this->sites->filterByOwner($user->id);

    // Get all frozen sites.
    $this->sites->filter(function($model) {
      return $model->isFrozen();
    });

    $sites = $this->sites->serialize();

    if (empty($sites)) {
      $this->log()->notice('No results');
    }
    else {
      foreach($sites as $site) {
        $output->writeln($site['name']);
      }

      if (!$this->confirm('Are you sure you want to delete these frozen sites?')) {
        return;
      }

      foreach($sites as $site_id => $site) {
        $output->write('Deleting ' . $site['name'] . '...');

        // Delete the site.
        $this->getSite($site_id)->delete();
        $output->writeln(' <info>Done!</>');
      }
      $this->log()->notice('Success!');
    }
  }
}
