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

use ILIAS\Data\ObjectId;
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

    public function setPublicAccessFile(ObjectId $object_id, string $type, string $file_identifier)
    {
        $this->export_handler->publicAccess()->repository()->handler()->storeElement(
            $this->export_handler->publicAccess()->repository()->element()->handler()
                ->withObjectId($object_id)
                ->withType($type)
                ->withIdentification($file_identifier)
        );
    }

    public function getPublicAccessFileIdentifier(ObjectId $object_id): string
    {
        return $this->export_handler->publicAccess()->repository()->handler()->getElement($object_id)->getIdentification();
    }

    public function getPublicAccessFileType(ObjectId $object_id): string
    {
        return $this->export_handler->publicAccess()->repository()->handler()->getElement($object_id)->getType();
    }

    public function downloadLinkOfPublicAccessFile(ReferenceId $reference_id): string
    {
        return (string) $this->export_handler->publicAccess()->link()->handler()->withReferenceId($reference_id)->getLink();
    }

    public function removePublicAccessFile(ObjectId $object_id): void
    {
        $element = $this->export_handler->publicAccess()->repository()->handler()->getElement($object_id);
        $this->export_handler->publicAccess()->repository()->handler()->deleteElement($element);
    }

    public function typeRestriction(): ilExportHandlerPublicAccessTypeRestrictionInterface
    {
        return $this->export_handler->publicAccess()->typeRestriction()->handler();
    }
}
