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
use ILIAS\Filesystem\Stream\FileStream;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;

interface ilHandlerInterface
{
    public function withObjectId(ObjectId $object_id): ilHandlerInterface;

    public function withResourceIdSerialized(string $resource_id_serialized): ilHandlerInterface;

    public function withOwnerId(int $owner_id): ilHandlerInterface;

    public function write(FileStream $stream, string $path_in_container): bool;

    public function writeZip(FileStream $zip_stream, string $path_in_container): bool;

    public function writeElement(ilHandlerInterface $other, string $path_in_container): bool;

    public function download(string $zip_file_name = ""): void;

    public function getStream(): FileStream;

    public function getObjectId(): ObjectId;

    public function getResourceId(): ResourceIdentification;

    public function getCreationDate(): DateTimeImmutable;

    public function getFileName(): string;

    public function getFileType(): string;

    public function getFileSize(): int;

    public function getOwnerId(): int;

    public function isStorable(): bool;
}
