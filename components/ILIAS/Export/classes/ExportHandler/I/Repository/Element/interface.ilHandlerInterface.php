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

namespace ILIAS\Export\ExportHandler\I\Repository\Element;

use DateTimeImmutable;
use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\Repository\ilResourceStakeholderInterface as ilExportHandlerRepositoryResourceStakeholderInterface;
use ILIAS\Filesystem\Stream\FileStream;
use ILIAS\Filesystem\Util\Archive\Unzip;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;

interface ilHandlerInterface
{
    public function withObjectId(ObjectId $object_id): ilHandlerInterface;

    public function withResourceId(ResourceIdentification $resource_id): ilHandlerInterface;

    public function withStakeholder(ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder): ilHandlerInterface;

    public function write(FileStream $stream, string $path_in_container): bool;

    public function writeZip(FileStream $zip_stream, string $path_in_container): bool;

    public function writeElement(ilHandlerInterface $other, string $path_in_container): bool;

    public function download(string $zip_file_name = ""): void;

    public function getZip(): Unzip;

    public function getStream(): FileStream;

    public function getObjectId(): ObjectId;

    public function getResourceId(): ResourceIdentification;

    public function getLastModified(): DateTimeImmutable;

    public function getFileName(): string;

    public function getFileNameWithoutExtension(): string;

    public function getFileType(): string;

    public function getDownloadURL(): string;

    public function getFileSize(): int;

    public function getStakeholder(): ilExportHandlerRepositoryResourceStakeholderInterface;

    public function isStorable(): bool;
}
