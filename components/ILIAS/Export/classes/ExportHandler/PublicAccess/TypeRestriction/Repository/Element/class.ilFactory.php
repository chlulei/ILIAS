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

namespace ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\Repository\Element;

use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessTypeRestrictionElementCollectionInterface;
use ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\Repository\Element\ilCollection as ilExportHandlerPublicAccessTypeRestrictionElementCollection;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilFactoryInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryElementFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface;
use ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\Repository\Element\ilHandler as ilExportHandlerPublicAccessTypeRestrictionRepositoryElement;

class ilFactory implements ilExportHandlerPublicAccessTypeRestrictionRepositoryElementFactoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler
    ) {
        $this->export_handler = $export_handler;
    }

    public function handler(): ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface
    {
        return new ilExportHandlerPublicAccessTypeRestrictionRepositoryElement();
    }

    public function collection(): ilExportHandlerPublicAccessTypeRestrictionElementCollectionInterface
    {
        return new ilExportHandlerPublicAccessTypeRestrictionElementCollection();
    }
}
