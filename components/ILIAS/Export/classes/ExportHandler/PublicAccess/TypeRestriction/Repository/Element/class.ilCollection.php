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

use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryElementCollectionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface;

class ilCollection implements ilExportHandlerPublicAccessTypeRestrictionRepositoryElementCollectionInterface
{
    protected array $elements;
    protected int $index;

    public function __construct()
    {
        $this->elements = [];
        $this->index = 0;
    }

    public function withElement(
        ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface $element
    ): ilExportHandlerPublicAccessTypeRestrictionRepositoryElementCollectionInterface {
        $clone = clone $this;
        $clone->elements[] = $element;
        return $clone;
    }

    public function types(): array
    {
        return array_map(function (ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface $element) { return $element->getAllowedType(); }, $this->elements);
    }

    public function valid(): bool
    {
        return isset($this->elements[$this->index]);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function current(): ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface
    {
        return $this->elements[$this->index];
    }

    public function count(): int
    {
        return count($this->elements);
    }
}
