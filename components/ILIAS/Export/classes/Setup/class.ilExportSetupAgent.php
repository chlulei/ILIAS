<?php

use ILIAS\Setup\Agent\NullAgent;
use ILIAS\Setup\ObjectiveCollection;
use ILIAS\Setup\Objective;
use ILIAS\Setup\Config;
use ILIAS\Setup\Metrics\Storage;

class ilExportSetupAgent extends NullAgent
{
    public function getUpdateObjective(Config $config = null): Objective
    {
        return new ObjectiveCollection(
            "Export",
            false,
            new ilDatabaseUpdateStepsExecutedObjective(new ilExportDBUpdateSteps())
        );
    }

    public function getStatusObjective(Storage $storage): Objective
    {
        return new ObjectiveCollection(
            'Component Export',
            true,
            new ilDatabaseUpdateStepsMetricsCollectedObjective($storage, new ilExportDBUpdateSteps())
        );
    }

    public function getMigrations(): array
    {
        return [new ilExportFilesToIRSSMigration()];
    }
}
