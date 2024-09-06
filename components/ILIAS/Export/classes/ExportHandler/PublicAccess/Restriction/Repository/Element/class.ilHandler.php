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

namespace ILIAS\Export\ExportHandler\PublicAccess\Restriction\Repository\Element;

use DateTimeImmutable;
use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessRestrictionRepositoryElementInterface;

class ilHandler implements ilExportHandlerPublicAccessRestrictionRepositoryElementInterface
{
    protected ObjectId $object_id;
    protected DateTimeImmutable $last_modified;
    protected string $export_option_id;

    public function __clone(): void
    {
        $this->last_modified = new DateTimeImmutable();
    }

    public function withObjectId(ObjectId $object_id): ilExportHandlerPublicAccessRestrictionRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->object_id = $object_id;
        return $clone;
    }

    public function withExportOptionId(string $type): ilExportHandlerPublicAccessRestrictionRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->export_option_id = $type;
        return $clone;
    }

    public function getObjectId(): ObjectId
    {
        return $this->object_id;
    }

    public function getExportOptionId(): string
    {
        return $this->export_option_id;
    }

    public function getLastModified(): DateTimeImmutable
    {
        return $this->last_modified;
    }

    public function isStorable(): bool
    {
        return isset($this->object_id) and isset($this->export_option_id) and isset($this->last_modified);
    }
}
