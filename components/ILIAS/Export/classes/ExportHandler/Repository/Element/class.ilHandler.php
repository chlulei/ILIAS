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
use ILIAS\Export\ExportHandler\I\Repository\Element\Wrapper\IRSS\ilHandlerInterface as ilExportHandlerRepositoryElementIRSSWrapperInterface;
use ILIAS\Filesystem\Stream\FileStream;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;

class ilHandler implements ilExportHandlerRepositoryElementInterface
{
    protected ilExportHandlerRepositoryElementIRSSWrapperInterface $irss_wrapper;
    protected ObjectId $object_id;
    protected string $resource_id_serialized;
    protected int $owner_id;

    public function __construct(
        ilExportHandlerRepositoryElementIRSSWrapperInterface $irss_wrapper
    ) {
        $this->irss_wrapper = $irss_wrapper;
    }

    public function withObjectId(ObjectId $object_id): ilExportHandlerRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->object_id = $object_id;
        return $clone;
    }

    public function withResourceIdSerialized(string $resource_id_serialized): ilExportHandlerRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->resource_id_serialized = $resource_id_serialized;
        return $clone;
    }

    public function withOwnerId(int $owner_id): ilExportHandlerRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->owner_id = $owner_id;
        return $clone;
    }

    public function write(
        FileStream $stream,
        string $path_in_container
    ): bool {
        if (!isset($this->resource_id_serialized)) {
            return false;
        }
        $success = $this->irss_wrapper->addStreamToContainer($this->resource_id_serialized, $stream, $path_in_container);
        if ($success) {
            $this->irss_wrapper->removeTmpFile($this->resource_id_serialized);
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

    public function getStream(): FileStream
    {
        return $this->irss_wrapper->getStream($this->resource_id_serialized);
    }

    public function download(string $zip_file_name = ""): void
    {
        $this->irss_wrapper->download($this->resource_id_serialized, $zip_file_name);
    }

    public function getObjectId(): ObjectId
    {
        return $this->object_id;
    }

    public function getResourceId(): ResourceIdentification
    {
        return $this->irss_wrapper->getResourceId($this->resource_id_serialized);
    }

    public function getCreationDate(): DateTimeImmutable
    {
        return $this->irss_wrapper->getCreationDate($this->resource_id_serialized);
    }

    public function getFileName(): string
    {
        return $this->irss_wrapper->getFileName($this->resource_id_serialized);
    }

    public function getFileType(): string
    {
        return "xml";
    }

    public function getFileSize(): int
    {
        return $this->irss_wrapper->getFileSize($this->resource_id_serialized);
    }

    public function getOwnerId(): int
    {
        return $this->owner_id;
    }

    public function isStorable(): bool
    {
        return isset($this->object_id) && isset($this->resource_id_serialized);
    }
}
