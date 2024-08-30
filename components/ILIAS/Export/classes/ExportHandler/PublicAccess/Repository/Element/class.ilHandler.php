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
use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessRepositoryElementInterface;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;

class ilHandler implements ilExportHandlerPublicAccessRepositoryElementInterface
{
    protected ReferenceId $reference_id;
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

    public function withReferenceId(ReferenceId $reference_id): ilExportHandlerPublicAccessRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->reference_id = $reference_id;
        return $clone;
    }

    public function withIdentification(string $identification): ilExportHandlerPublicAccessRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->resource_Id = $identification;
        return $clone;
    }

    public function getIdentification(): string
    {
        return $this->resource_Id;
    }

    public function getReferenceId(): ReferenceId
    {
        return $this->reference_id;
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
        return isset($this->resource_Id) and isset($this->last_modified) and isset($this->reference_id);
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
