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

use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilHandlerInterface as ilExportHandlerConsumerExportOptionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\ilFactoryInterface as ilExportHandlerConsumerFileFactoryInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Info\File\ilCollectionInterface as ilExportHandlerFileInfoCollectionInterface;
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

    public function collection(): ilExportHandlerFileInfoCollectionInterface
    {
        return $this->export_handler->info()->file()->collection();
    }

    public function fileInfoFromSplFileInfo(
        SplFileInfo $spl_file_info,
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerConsumerExportOptionInterface $export_option
    ): ilExportHandlerFileInfoInterface {
        $object_id = new ObjectId($context->exportObject()->getId());
        $file_info = $this->export_handler->info()->file()->handler()
            ->withSplFileInfo($spl_file_info)
            ->withType($export_option->getExportType())
            ->withPublicAccessPossible($export_option->isPublicAccessPossible());
        return $file_info->withPublicAccessEnabled(
            $export_option->isPublicAccessPossible() and
            $this->export_handler->publicAccess()->handler()->hasPublicAccessFile($object_id) and
            $this->export_handler->publicAccess()->handler()->getPublicAccessFileType($object_id) === $export_option->getExportOptionId() and
            $this->export_handler->publicAccess()->handler()->getPublicAccessFileIdentifier($object_id) === $file_info->getFileIdentifier()
        );
    }

    public function fileInfoFromResourceId(
        ResourceIdentification $resource_id,
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerConsumerExportOptionInterface $export_option
    ): ilExportHandlerFileInfoInterface {
        $object_id = new ObjectId($context->exportObject()->getId());
        $file_info = $this->export_handler->info()->file()->handler()
            ->withResourceId($resource_id)
            ->withType($export_option->getExportType())
            ->withPublicAccessPossible($export_option->isPublicAccessPossible());
        return $file_info->withPublicAccessEnabled(
            $export_option->isPublicAccessPossible() and
            $this->export_handler->publicAccess()->handler()->hasPublicAccessFile($object_id) and
            $this->export_handler->publicAccess()->handler()->getPublicAccessFileType($object_id) === $export_option->getExportOptionId() and
            $this->export_handler->publicAccess()->handler()->getPublicAccessFileIdentifier($object_id) === $file_info->getFileIdentifier()
        );
    }
}
