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

namespace ILIAS\Export\ExportHandler\Consumer\File\Identification;

use ILIAS\Export\ExportHandler\Consumer\File\Identification\ilCollection as ilExportHandlerConsumerFileIdentificationCollection;
use ILIAS\Export\ExportHandler\Consumer\File\Identification\ilHandler as ilExportHandlerConsumerFileIdentification;
use ILIAS\Export\ExportHandler\I\Consumer\File\Identification\ilCollectionInterface as ilExportHandlerConsumerFileIdentificationCollectionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\Identification\ilFactoryInterface as ilExportHandlerConsumerFileIdentificationFactoryInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\Identification\ilHandlerInterface as ilExportHandlerConsumerFileIdentificationInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;

class ilFactory implements ilExportHandlerConsumerFileIdentificationFactoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(ilExportHandlerFactoryInterface $export_handler)
    {
        $this->export_handler = $export_handler;
    }

    public function handler(): ilExportHandlerConsumerFileIdentificationInterface
    {
        return new ilExportHandlerConsumerFileIdentification();
    }

    public function collection(): ilExportHandlerConsumerFileIdentificationCollectionInterface
    {
        return new ilExportHandlerConsumerFileIdentificationCollection();
    }
}
