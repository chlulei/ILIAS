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
use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessRepositoryElementInterface;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;

class ilHandler implements ilExportHandlerPublicAccessRepositoryElementInterface
{
    protected ObjectId $object_id;
    protected string $resource_Id;
    protected string $export_option_id;
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

    public function withObjectId(ObjectId $object_id): ilExportHandlerPublicAccessRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->object_id = $object_id;
        return $clone;
    }

    public function withIdentification(string $identification): ilExportHandlerPublicAccessRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->resource_Id = $identification;
        return $clone;
    }

    public function withExportOptionId(string $type): ilExportHandlerPublicAccessRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->export_option_id = $type;
        return $clone;
    }

    public function getExportOptionId(): string
    {
        return $this->export_option_id;
    }

    public function getIdentification(): string
    {
        return $this->resource_Id ?? "";
    }

    public function getObjectId(): ObjectId
    {
        return $this->object_id;
    }

    public function getLastModified(): DateTimeImmutable
    {
        return $this->last_modified;
    }

    public function download(string $zip_file_name = ""): void
    {
        $rid = $this->irss->manage()->find($this->getIdentification());
        if(!is_null($rid)) {
            $this->downloadFromIRSS($rid, $zip_file_name);
        }
    }

    public function isStorable(): bool
    {
        return isset($this->object_id) and isset($this->last_modified) and isset($this->resource_Id) and isset($this->export_option_id);
    }

    protected function downloadFromIRSS(ResourceIdentification $rid, string $zip_file_name): void
    {
        $download = $this->irss->consume()->download($rid);
        if ($zip_file_name !== "") {
            $download = $download->overrideFileName($zip_file_name);
        }
        $download->run();
    }
}
