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

namespace ILIAS\Export\ExportHandler\I\Info\File;

use DateTimeImmutable;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;
use SplFileInfo;

interface ilHandlerInterface
{
    public function withPublicAccessPossible(bool $enabled): ilHandlerInterface;

    public function withPublicAccessEnabled(bool $enabled): ilHandlerInterface;

    public function withResourceId(ResourceIdentification $resource_id, string $type): ilHandlerInterface;

    public function withContainerResourceId(ResourceIdentification $resource_id, string $type): ilHandlerInterface;

    public function withSplFileInfo(SplFileInfo $splFileInfo, string $type): ilHandlerInterface;

    public function getPublicAccessPossible(): bool;

    public function getPublicAccessEnabled(): bool;

    public function getFileSize(): int;

    public function getFileName(): string;

    public function getFileType(): string;

    public function getDownloadInfo(): string;

    public function getLastChanged(): DateTimeImmutable;

    public function getLastChangedTimestamp(): int;

    public function getFileIdentifier(): string;
}
