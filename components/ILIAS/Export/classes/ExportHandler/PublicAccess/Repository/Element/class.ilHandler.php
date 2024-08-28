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

namespace ILIAS\Export\ExportHandler\PublicAccess\Repository\Element;

use DateTimeImmutable;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessRepositoryElementInterface;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;

class ilHandler implements ilExportHandlerPublicAccessRepositoryElementInterface
{
    protected int $object_id;
    protected string $resource_Id;
    protected DateTimeImmutable $last_modified;
    protected ResourcesStorageService $irss;

    public function __construct(
        ResourcesStorageService $irss
    ) {
        $this->irss = $irss;
    }

    public function __clone(): void
    {
        $this->last_modified = new DateTimeImmutable();
    }

    public function withObjectId(int $object_id): ilExportHandlerPublicAccessRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->object_id = $object_id;
        return $clone;
    }

    public function withIdentification(string $resource_id): ilExportHandlerPublicAccessRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->resource_Id = $resource_id;
        return $clone;
    }

    public function getIdentification(): string
    {
        return $this->resource_Id;
    }

    public function getObjectId(): int
    {
        return $this->object_id;
    }

    public function getLastModified(): DateTimeImmutable
    {
        return $this->last_modified;
    }

    public function download(string $zip_file_name = ""): void
    {
        $download = $this->irss->consume()->download($this->irss->manage()->find($this->getIdentification()));
        if ($zip_file_name !== "") {
            $download = $download->overrideFileName($zip_file_name);
        }
        $download->run();
    }

    public function isStorable(): bool
    {
        return isset($this->resource_Id) and isset($this->last_modified) and isset($this->object_id);
    }
}
