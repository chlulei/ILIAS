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

namespace ILIAS\Export\ExportHandler\PublicAccess;

use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\ilHandlerInterface as ilExportHandlerPublicAccessInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\ilHandlerInterface as ilExportHandlerPublicAccessTypeRestrictionInterface;

class ilHandler implements ilExportHandlerPublicAccessInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler
    ) {
        $this->export_handler = $export_handler;
    }

    public function setPublicAccessFile(ReferenceId $reference_id, string $file_identifier)
    {
        $this->export_handler->publicAccess()->repository()->handler()->storeElement(
            $this->export_handler->publicAccess()->repository()->element()->handler()
                ->withReferenceId($reference_id)
                ->withIdentification($file_identifier)
        );
    }

    public function removePublicAccessFile(ReferenceId $reference_id)
    {
        $element = $this->export_handler->publicAccess()->repository()->handler()->getElement($reference_id);
        $this->export_handler->publicAccess()->repository()->handler()->deleteElement($element);
    }

    public function typeRestriction(): ilExportHandlerPublicAccessTypeRestrictionInterface
    {
        return $this->export_handler->publicAccess()->typeRestriction()->handler();
    }
}
