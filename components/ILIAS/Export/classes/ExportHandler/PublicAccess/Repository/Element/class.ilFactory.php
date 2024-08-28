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

namespace ILIAS\Export\ExportHandler\PublicAccess\Repository\Element;

use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessRepositoryCollectionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element\ilFactoryInterface as ilExportHandlerPublicAccessRepositoryElementFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessRepositoryElementInterface;
use ILIAS\Export\ExportHandler\PublicAccess\Repository\Element\ilCollection as ilExportHandlerPublicAccessRepositoryCollection;
use ILIAS\Export\ExportHandler\PublicAccess\Repository\Element\ilHandler as ilExportHandlerPublicAccessRepositoryElement;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;

class ilFactory implements ilExportHandlerPublicAccessRepositoryElementFactoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;
    protected ResourcesStorageService $irss;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler,
        ResourcesStorageService $irss
    ) {
        $this->export_handler = $export_handler;
        $this->irss = $irss;
    }

    public function handler(): ilExportHandlerPublicAccessRepositoryElementInterface
    {
        return new ilExportHandlerPublicAccessRepositoryElement($this->irss);
    }

    public function collection(): ilExportHandlerPublicAccessRepositoryCollectionInterface
    {
        return new ilExportHandlerPublicAccessRepositoryCollection();
    }
}
