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

namespace ILIAS\Export\ExportHandler\I\Repository\Element\Wrapper\IRSS;

use DateTimeImmutable;
use ILIAS\Filesystem\Stream\FileStream;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;

interface ilHandlerInterface
{
    public function getResourceId(
        string $resource_id_serialized
    ): null|ResourceIdentification;

    public function removeTmpFile(
        string $resource_id_serialized
    ): void;

    public function getStream(
        string $resource_id_serialized
    ): FileStream;

    public function getCreationDate(
        string $resource_id_serialized
    ): DateTimeImmutable;

    public function getFileName(
        string $resource_id_serialized
    ): string;

    public function getFileSize(
        string $resource_id_serialized
    ): int;

    public function download(
        string $resource_id_serialized,
        string $zip_file_name = ""
    ): void;

    public function addStreamToContainer(
        string $resource_id_serialized,
        FileStream $stream,
        string $path_in_container
    ): bool;
}
