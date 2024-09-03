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

namespace ILIAS\Export\ExportHandler\I\PublicAccess\Repository;

use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessRepositoryElementCollectionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessRepositoryElementInterface;

interface ilHandlerInterface
{
    public function storeElement(ilExportHandlerPublicAccessRepositoryElementInterface $element): bool;

    public function getElement(ObjectId $object_id): ilExportHandlerPublicAccessRepositoryElementInterface;

    public function hasElement(ilExportHandlerPublicAccessRepositoryElementInterface $element): bool;

    public function getElements(): ilExportHandlerPublicAccessRepositoryElementCollectionInterface;

    public function deleteElement(ilExportHandlerPublicAccessRepositoryElementInterface $element): bool;

    public function deleteElements(ilExportHandlerPublicAccessRepositoryElementCollectionInterface $elements): bool;
}
