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

namespace ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\Repository;

use ilDBInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilFactoryInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryElementFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\ilFactoryInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\ilHandlerInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryInterface;
use ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\Repository\Element\ilFactory as ilExportHandlerPublicAccessTypeRestrictionRepositoryElementFactory;
use ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\Repository\ilHandler as ilExportHandlerPublicAccessTypeRestrictionRepository;

class ilFactory implements ilExportHandlerPublicAccessTypeRestrictionRepositoryFactoryInterface
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

    public function handler(): ilExportHandlerPublicAccessTypeRestrictionRepositoryInterface
    {
        return new ilExportHandlerPublicAccessTypeRestrictionRepository($this->export_handler, $this->db);
    }

    public function element(): ilExportHandlerPublicAccessTypeRestrictionRepositoryElementFactoryInterface
    {
        return new ilExportHandlerPublicAccessTypeRestrictionRepositoryElementFactory($this->export_handler);
    }
}
