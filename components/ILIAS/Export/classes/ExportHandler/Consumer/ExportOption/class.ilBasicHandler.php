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

namespace ILIAS\Export\ExportHandler\Consumer\ExportOption;

use ILIAS\Data\ObjectId;
use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilHandlerInterface as ilExportHandlerConsumerExportOptionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessTypeRestrictionRepitoryElementCollectionInterface;

abstract class ilBasicHandler implements ilExportHandlerConsumerExportOptionInterface
{
    public function onPublicAccessTypeRestrictionsChanged(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerPublicAccessTypeRestrictionRepitoryElementCollectionInterface $allowed_types
    ): void {
        $is_allowed_type = false;
        foreach ($allowed_types as $allowed_type) {
            if ($allowed_type->getAllowedType() === $this->getExportType()) {
                $is_allowed_type = true;
                break;
            }
        }
        if(!$is_allowed_type) {
            $context->publicAccess()->removePublicAccessFile(new ObjectId($context->exportObject()->getId()));
        }
    }
}
