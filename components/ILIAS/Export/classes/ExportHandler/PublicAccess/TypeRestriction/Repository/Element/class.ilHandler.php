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

namespace ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\Repository\Element;

use DateTimeImmutable;
use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface;

class ilHandler implements ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface
{
    protected ObjectId $object_id;
    protected string $type;
    protected DateTimeImmutable $last_modified;

    public function __clone(): void
    {
        $this->last_modified = new DateTimeImmutable();
    }

    public function withObjectId(ObjectId $object_id): ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->object_id = $object_id;
        return $clone;
    }

    public function withAllowedType(string $type): ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface
    {
        $clone = clone $this;
        $clone->type = $type;
        return $clone;
    }

    public function getObjectId(): ObjectId
    {
        return $this->object_id;
    }

    public function getAllowedType(): string
    {
        return $this->type;
    }

    public function getLastModified(): DateTimeImmutable
    {
        return $this->last_modified;
    }

    public function isStorable(): bool
    {
        return isset($this->object_id) and isset($this->type) and isset($this->last_modified);
    }
}
