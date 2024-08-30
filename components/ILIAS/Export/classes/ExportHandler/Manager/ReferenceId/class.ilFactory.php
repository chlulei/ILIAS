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

namespace ILIAS\Export\ExportHandler\Manager\ReferenceId;

use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Manager\ReferenceId\ilCollectionInterface as ilExportHandlerManagerReferenceIdCollectionInterface;
use ILIAS\Export\ExportHandler\I\Manager\ReferenceId\ilFactoryInterface as ilExportHandlerManagerReferenceIdFactoryInterface;
use ILIAS\Export\ExportHandler\I\Manager\ReferenceId\ilHandlerInterface as ilExportHandlerManagerReferenceIdInterface;
use ILIAS\Export\ExportHandler\Manager\ReferenceId\ilCollection as ilExportHandlerManagerReferenceIdCollection;
use ILIAS\Export\ExportHandler\Manager\ReferenceId\ilHandler as ilExportHandlerManagerReferenceId;

class ilFactory implements ilExportHandlerManagerReferenceIdFactoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(ilExportHandlerFactoryInterface $export_handler)
    {
        $this->export_handler = $export_handler;
    }

    public function handler(): ilExportHandlerManagerReferenceIdInterface
    {
        return new ilExportHandlerManagerReferenceId();
    }

    public function collection(): ilExportHandlerManagerReferenceIdCollectionInterface
    {
        return new ilExportHandlerManagerReferenceIdCollection();
    }
}
