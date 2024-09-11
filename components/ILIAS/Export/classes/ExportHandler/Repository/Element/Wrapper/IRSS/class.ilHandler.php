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

namespace ILIAS\Export\ExportHandler\Repository\Element\Wrapper\IRSS;

use DateTimeImmutable;
use ILIAS\Export\ExportHandler\I\Repository\Element\Wrapper\IRSS\ilHandlerInterface as ilExportHandlerRepositoryElementIRSSWrapperInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilHandlerInterface as ilExportHandlerRepositoryInterface;
use ILIAS\Filesystem\Stream\FileStream;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;

class ilHandler implements ilExportHandlerRepositoryElementIRSSWrapperInterface
{
    protected ResourcesStorageService $irss;

    public function __construct(
        ResourcesStorageService $irss
    ) {
        $this->irss = $irss;
    }

    public function getStream(string $resource_id_serialized): FileStream
    {
        return $this->irss->consume()->stream($this->getResourceId($resource_id_serialized))
            ->getStream();
    }

    public function removeTmpFile(string $resource_id_serialized): void
    {
        $this->irss->manageContainer()->removePathInsideContainer(
            $this->getResourceId($resource_id_serialized),
            ilExportHandlerRepositoryInterface::TMP_FILE_PATH
        );
    }

    public function getResourceId(string $resource_id_serialized): null|ResourceIdentification
    {
        return $this->irss->manageContainer()->find($resource_id_serialized);
    }

    public function getCreationDate(string $resource_id_serialized): DateTimeImmutable
    {
        return $this->irss->manageContainer()->getResource($this->getResourceId($resource_id_serialized))
            ->getCurrentRevision()->getInformation()->getCreationDate();
    }

    public function getFileName(string $resource_id_serialized): string
    {
        return $this->irss->manageContainer()->getResource($this->getResourceId($resource_id_serialized))
            ->getCurrentRevision()->getInformation()->getTitle();
    }

    public function getFileSize(string $resource_id_serialized): int
    {
        return $this->irss->manageContainer()->getResource($this->getResourceId($resource_id_serialized))
            ->getCurrentRevision()->getInformation()->getSize();
    }

    public function download(
        string $resource_id_serialized,
        string $zip_file_name = ""
    ): void {
        $download = $this->irss->consume()->download($this->getResourceId($resource_id_serialized));
        if ($zip_file_name !== "") {
            $download = $download->overrideFileName($zip_file_name);
        }
        $download->run();
    }

    public function addStreamToContainer(
        string $resource_id_serialized,
        FileStream $stream,
        string $path_in_container
    ): bool {
        return $this->irss->manageContainer()->addStreamToContainer(
            $this->getResourceId($resource_id_serialized),
            $stream,
            $path_in_container
        );
    }
}
