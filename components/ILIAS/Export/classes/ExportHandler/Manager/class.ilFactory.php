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

namespace ILIAS\Export\ExportHandler\Manager;

use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Manager\ilFactoryInterface as ilExportHandlerManagerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Manager\ilHandlerInterface as ilExportHandlerManagerInterface;
use ILIAS\Export\ExportHandler\I\Manager\ReferenceId\ilFactoryInterface as ilExportHandlerManagerReferenceIdFactoryInterface;
use ILIAS\Export\ExportHandler\Manager\ReferenceId\ilFactory as ilExportHandlerManagerReferenceIdFactory;
use ILIAS\Export\ExportHandler\Manager\ilHandler as ilExportHandlerManager;

class ilFactory implements ilExportHandlerManagerFactoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(ilExportHandlerFactoryInterface $export_handler)
    {
        $this->export_handler = $export_handler;
    }

    public function handler(): ilExportHandlerManagerInterface
    {
        return new ilExportHandlerManager($this->export_handler);
    }

    public function referenceId(): ilExportHandlerManagerReferenceIdFactoryInterface
    {
        return new ilExportHandlerManagerReferenceIdFactory($this->export_handler);
    }
}
