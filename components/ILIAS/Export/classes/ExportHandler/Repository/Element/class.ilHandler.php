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

namespace ILIAS\Export\ExportHandler\Repository\Element;

use DateTimeImmutable;
use ILIAS\components\ResourceStorage\Container\Wrapper\ZipReader;
use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilHandlerInterface as ilExportHandlerRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilHandlerInterface as ilExportHandlerRepositoryInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilResourceStakeholderInterface as ilExportHandlerRepositoryResourceStakeholderInterface;
use ILIAS\Filesystem\Stream\FileStream;
use ILIAS\Filesystem\Util\Archive\Unzip;
use ILIAS\Filesystem\Util\Archive\UnzipOptions;
use ILIAS\Filesystem\Util\Archive\ZipDirectoryHandling;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;

class ilHandler implements ilExportHandlerRepositoryElementInterface
{
    protected ResourcesStorageService $irss;
    protected DateTimeImmutable $last_modified;
    protected ResourceIdentification $resource_id;
    protected ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder;
    protected ObjectId $object_id;

    public function __construct(
        ResourcesStorageService $irss,
    ) {
        $this->irss = $irss;
    }

    protected function removeTmpFile(): void
    {
        $this->irss->manageContainer()->removePathInsideContainer(
            $this->getResourceId(),
            ilExportHandlerRepositoryInterface::TMP_FILE_PATH
        );
    }

    public function withObjectId(ObjectId $object_id): ilExportHandlerRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->object_id = $object_id;
        return $clone;
    }

    public function withResourceId(ResourceIdentification $resource_id): ilExportHandlerRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->resource_id = $resource_id;
        $clone->last_modified = $this->irss->manageContainer()->getResource($resource_id)
            ->getCurrentRevision()->getInformation()->getCreationDate();
        return $clone;
    }

    public function withStakeholder(ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder): ilExportHandlerRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->stakeholder = $stakeholder;
        return $clone;
    }

    public function write(
        FileStream $stream,
        string $path_in_container
    ): bool {
        if (!isset($this->resource_id)) {
            global $DIC;
            return false;
        }
        $this->last_modified = new DateTimeImmutable();
        $success = $this->irss->manageContainer()->addStreamToContainer($this->getResourceId(), $stream, $path_in_container);
        if ($success) {
            $this->removeTmpFile();
        }
        return $success;
    }

    public function writeZip(
        FileStream $zip_stream,
        string $path_in_container
    ): bool {
        $zip_reader = new ZipReader($zip_stream);
        $zip_structure = $zip_reader->getStructure();
        $success = true;
        foreach ($zip_structure as $path_inside_zip => $item) {
            if ($item['is_dir']) {
                continue;
            }
            $stream = $zip_reader->getItem($path_inside_zip, $zip_structure)[0];
            $success = $success and $this->write($stream, $path_in_container . DIRECTORY_SEPARATOR . $path_inside_zip);
        }
        return $success;
    }

    public function writeElement(
        ilExportHandlerRepositoryElementInterface $other,
        string $path_in_container
    ): bool {
        return $this->writeZip($other->getStream(), $path_in_container);
    }

    public function getZip(): Unzip
    {
        return $this->irss->consume()->containerZIP($this->getResourceId())->getZIP((new UnzipOptions())->withDirectoryHandling(ZipDirectoryHandling::KEEP_STRUCTURE));
    }

    public function getStream(): FileStream
    {
        return $this->irss->consume()->stream($this->getResourceId())->getStream();
    }

    public function download(string $zip_file_name = ""): void
    {
        $download = $this->irss->consume()->download($this->getResourceId());
        if ($zip_file_name !== "") {
            $download = $download->overrideFileName($zip_file_name);
        }
        $download->run();
    }

    public function getObjectId(): ObjectId
    {
        return $this->object_id;
    }

    public function getResourceId(): ResourceIdentification
    {
        return $this->resource_id;
    }

    public function getLastModified(): DateTimeImmutable
    {
        return $this->last_modified;
    }

    public function getFileName(): string
    {
        return $this->irss->manageContainer()->getResource($this->getResourceId())
            ->getCurrentRevision()->getInformation()->getTitle();
    }

    public function getFileNameWithoutExtension(): string
    {
        $filename = $this->getFileName();
        return substr($filename, 0, strlen($filename) - 4);
    }

    public function getFileType(): string
    {
        return "xml";
    }

    public function getDownloadURL(): string
    {
        return (string) $this->irss->consume()->containerURI($this->getResourceId())->getURI();
    }

    public function getFileSize(): int
    {
        return $this->irss->manageContainer()->getResource($this->getResourceId())
            ->getCurrentRevision()->getInformation()->getSize();
    }

    public function getStakeholder(): ilExportHandlerRepositoryResourceStakeholderInterface
    {
        return $this->stakeholder;
    }

    public function isStorable(): bool
    {
        return isset($this->last_modified) && isset($this->object_id) && isset($this->resource_id);
    }
}
