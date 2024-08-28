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

namespace ILIAS\Export\ExportHandler\Consumer\ExportOption;

use ILIAS\Export\ExportHandler\Consumer\ExportOption\ilCollection as ilExportHandlerConsumerExportOptionCollection;
use ILIAS\Export\ExportHandler\Consumer\ExportOption\ilXMLRepoHandler as ilExportHandlerConsumerExportOptionBasicXML;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilCollectionInterface as ilExportHandlerConsumerExportOptionCollectionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilFactoryInterface as ilExportHandlerConsumerExportOptionFactoryInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilHandlerInterface as ilExportHandlerConsumerExportOptionInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;

class ilFactory implements ilExportHandlerConsumerExportOptionFactoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler
    ) {
        $this->export_handler = $export_handler;
    }

    public function collection(): ilExportHandlerConsumerExportOptionCollectionInterface
    {
        return new ilExportHandlerConsumerExportOptionCollection($this->export_handler);
    }

    public function basicXml(): ilExportHandlerConsumerExportOptionInterface
    {
        return new ilExportHandlerConsumerExportOptionBasicXML($this->export_handler);
    }
}
