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

namespace ILIAS\Export\ExportHandler\Consumer\File;

use ILIAS\Export\ExportHandler\Consumer\File\Identification\ilFactory as ilExportHandlerConsumerFileIdentificationFactory;
use ILIAS\Export\ExportHandler\Consumer\File\ilCollection as ilExportHandlerConsumerFileCollection;
use ILIAS\Export\ExportHandler\I\Consumer\File\Identification\ilFactoryInterface as ilExportHandlerConsumerFileIdentificationFactoryInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\ilCollectionInterface as ilExportHandlerConsumerFileCollectionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\ilFactoryInterface as ilExportHandlerConsumerFileFactoryInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Info\File\ilHandlerInterface as ilExportHandlerFileInfoInterface;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;
use SplFileInfo;

class ilFactory implements ilExportHandlerConsumerFileFactoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(ilExportHandlerFactoryInterface $export_handler)
    {
        $this->export_handler = $export_handler;
    }

    public function collection(): ilExportHandlerConsumerFileCollectionInterface
    {
        return new ilExportHandlerConsumerFileCollection();
    }

    public function identification(): ilExportHandlerConsumerFileIdentificationFactoryInterface
    {
        return new ilExportHandlerConsumerFileIdentificationFactory($this->export_handler);
    }

    public function fileInfoFromSplFileInfo(
        SplFileInfo $spl_file_info,
        string $type,
        bool $public_access_possible
    ): ilExportHandlerFileInfoInterface {
        return $this->export_handler->info()->file()->handler()
            ->withSplFileInfo($spl_file_info, $type)
            ->withPublicAccessPossible($public_access_possible);
    }

    public function fileInfoFromResourceId(
        ResourceIdentification $resource_id,
        string $type,
        bool $public_access_possible
    ): ilExportHandlerFileInfoInterface {
        return $this->export_handler->info()->file()->handler()
            ->withResourceId($resource_id, $type)
            ->withPublicAccessPossible($public_access_possible);
    }
}
