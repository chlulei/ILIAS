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

namespace ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository;

use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessTypeRestrictionElementCollectionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface;

interface ilHandlerInterface
{
    public const TABLE_NAME = "export_pub_acc_types";

    public function addAllowedType(ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface $element): bool;

    public function removeAllowedType(ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface $element): bool;

    public function getAllowedTypes(ObjectId $object_id): ilExportHandlerPublicAccessTypeRestrictionElementCollectionInterface;

    public function isTypeAllowed(ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface $element): bool;
}
