<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

namespace ILIAS\Export\ExportHandler\PublicAccess\Restriction;

use ilDBInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\ilFactoryInterface as ilExportHandlerPublicAccessRestrictionFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\ilHandlerInterface as ilExportHandlerPublicAccessRestrictionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\Repository\ilFactoryInterface as ilExportHandlerPublicAccessRestrictionRepositoryFactoryInterface;
use ILIAS\Export\ExportHandler\PublicAccess\Restriction\ilHandler as ilExportHandlerPublicAccessRestriction;
use ILIAS\Export\ExportHandler\PublicAccess\Restriction\Repository\ilFactory as ilExportHandlerPublicAccessRestrictionRepositoryFactory;

class ilFactory implements ilExportHandlerPublicAccessRestrictionFactoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;
    protected ilDBInterface $db;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler,
        ilDBInterface $db
    ) {
        $this->export_handler = $export_handler;
        $this->db = $db;
    }

    public function handler(): ilExportHandlerPublicAccessRestrictionInterface
    {
        return new ilExportHandlerPublicAccessRestriction($this->export_handler);
    }

    public function repository(): ilExportHandlerPublicAccessRestrictionRepositoryFactoryInterface
    {
        return new ilExportHandlerPublicAccessRestrictionRepositoryFactory($this->export_handler, $this->db);
    }
}
