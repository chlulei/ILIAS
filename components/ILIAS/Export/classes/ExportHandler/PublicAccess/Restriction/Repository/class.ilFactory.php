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

namespace ILIAS\Export\ExportHandler\PublicAccess\Restriction\Repository;

use ilDBInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\Repository\Element\ilFactoryInterface as ilExportHandlerPublicAccessRestrictionRepositoryElementFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\Repository\ilFactoryInterface as ilExportHandlerPublicAccessRestrictionRepositoryFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\Repository\ilHandlerInterface as ilExportHandlerPublicAccessRestrictionRepositoryInterface;
use ILIAS\Export\ExportHandler\PublicAccess\Restriction\Repository\Element\ilFactory as ilExportHandlerPublicAccessRestrictionRepositoryElementFactory;
use ILIAS\Export\ExportHandler\PublicAccess\Restriction\Repository\ilHandler as ilExportHandlerPublicAccessRestrictionRepository;

class ilFactory implements ilExportHandlerPublicAccessRestrictionRepositoryFactoryInterface
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

    public function handler(): ilExportHandlerPublicAccessRestrictionRepositoryInterface
    {
        return new ilExportHandlerPublicAccessRestrictionRepository($this->export_handler, $this->db);
    }

    public function element(): ilExportHandlerPublicAccessRestrictionRepositoryElementFactoryInterface
    {
        return new ilExportHandlerPublicAccessRestrictionRepositoryElementFactory($this->export_handler);
    }
}
