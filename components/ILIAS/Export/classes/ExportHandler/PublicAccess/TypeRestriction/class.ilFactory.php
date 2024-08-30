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

namespace ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction;

use ilDBInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\ilFactoryInterface as ilExportHandlerPublicAccessTypeRestrictionFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\ilHandlerInterface as ilExportHandlerPublicAccessTypeRestrictionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\ilFactoryInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryFactoryInterface;
use ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\ilHandler as ilExportHandlerPublicAccessTypeRestriction;
use ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\Repository\ilFactory as ilExportHandlerPublicAccessTypeRestrictionRepositoryFactory;

class ilFactory implements ilExportHandlerPublicAccessTypeRestrictionFactoryInterface
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

    public function handler(): ilExportHandlerPublicAccessTypeRestrictionInterface
    {
        return new ilExportHandlerPublicAccessTypeRestriction($this->export_handler);
    }

    public function repository(): ilExportHandlerPublicAccessTypeRestrictionRepositoryFactoryInterface
    {
        return new ilExportHandlerPublicAccessTypeRestrictionRepositoryFactory($this->export_handler, $this->db);
    }
}
