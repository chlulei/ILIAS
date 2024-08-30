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

namespace ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction;

use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\ilHandlerInterface as ilExportHandlerPublicAccessTypeRestrictionInterface;

class ilHandler implements ilExportHandlerPublicAccessTypeRestrictionInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler
    ) {
        $this->export_handler = $export_handler;
    }

    public function addAllowedType(ReferenceId $reference_id, string $type): bool
    {
        return $this->export_handler->publicAccess()->typeRestriction()->repository()->handler()->addAllowedType(
            $this->export_handler->publicAccess()->typeRestriction()->repository()->element()->handler()
                ->withReferenceId($reference_id)
                ->withAllowedType($type)
        );
    }

    public function removeAllowedType(ReferenceId $reference_id, string $type): bool
    {
        return $this->export_handler->publicAccess()->typeRestriction()->repository()->handler()->removeAllowedType(
            $this->export_handler->publicAccess()->typeRestriction()->repository()->element()->handler()
                ->withReferenceId($reference_id)
                ->withAllowedType($type)
        );
    }

    public function isTypeAllowed(ReferenceId $reference_id, string $type): bool
    {
        return $this->export_handler->publicAccess()->typeRestriction()->repository()->handler()->isTypeAllowed(
            $this->export_handler->publicAccess()->typeRestriction()->repository()->element()->handler()
                ->withReferenceId($reference_id)
                ->withAllowedType($type)
        );
    }
}
