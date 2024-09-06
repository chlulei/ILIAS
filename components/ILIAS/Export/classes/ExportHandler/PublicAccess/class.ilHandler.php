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
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\ilHandlerInterface as ilExportHandlerPublicAccessRestrictionInterface;

class ilHandler implements ilExportHandlerPublicAccessInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;
    protected array $cache;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler
    ) {
        $this->export_handler = $export_handler;
        $this->cache = [];
    }

    public function setPublicAccessFile(
        ObjectId $object_id,
        string $type,
        string $file_identifier
    ): void {
        $success = $this->export_handler->publicAccess()->repository()->handler()->storeElement(
            $this->export_handler->publicAccess()->repository()->element()->handler()
                ->withObjectId($object_id)
                ->withExportOptionId($type)
                ->withIdentification($file_identifier)
        );
        if ($success) {
            $this->updateCache($object_id, $type, $file_identifier);
        }
    }

    public function hasPublicAccessFile(
        ObjectId $object_id
    ): bool {
        if ($this->isCached($object_id)) {
            return true;
        }
        $element = $this->export_handler->publicAccess()->repository()->handler()->getElement($object_id);
        if (is_null($element)) {
            return false;
        }
        $this->updateCache(
            $object_id,
            $element->getExportOptionId(),
            $element->getIdentification()
        );
        return true;
    }

    public function getPublicAccessFileIdentifier(
        ObjectId $object_id
    ): string {
        if (!$this->isCached($object_id)) {
            $element = $this->export_handler->publicAccess()->repository()->handler()->getElement($object_id);
            $this->updateCache(
                $object_id,
                $element->getExportOptionId(),
                $element->getIdentification()
            );
        }
        return $this->getCachedFileIdentifier($object_id);
    }

    public function getPublicAccessFileType(
        ObjectId $object_id
    ): string {
        if (!$this->isCached($object_id)) {
            $element = $this->export_handler->publicAccess()->repository()->handler()->getElement($object_id);
            $this->updateCache(
                $object_id,
                $element->getExportOptionId(),
                $element->getIdentification()
            );
        }
        return $this->getCachedType($object_id);
    }

    public function downloadLinkOfPublicAccessFile(
        ReferenceId $reference_id
    ): string {
        return (string) $this->export_handler->publicAccess()->link()->handler()->withReferenceId($reference_id)->getLink();
    }

    public function removePublicAccessFile(
        ObjectId $object_id
    ): void {
        $element = $this->export_handler->publicAccess()->repository()->handler()->getElement($object_id);
        $success = $this->export_handler->publicAccess()->repository()->handler()->deleteElement($element);
        if (
            $success and
            $this->isCached($object_id)
        ) {
            $this->removeCache($object_id);
        }
    }

    public function typeRestriction(): ilExportHandlerPublicAccessRestrictionInterface
    {
        return $this->export_handler->publicAccess()->restriction()->handler();
    }

    protected function updateCache(
        ObjectId $object_id,
        string $type,
        string $file_identifier
    ): void {
        $this->cache[$object_id->toInt()]["type"] = $type;
        $this->cache[$object_id->toInt()]["file_identifier"] = $file_identifier;
    }

    protected function getCachedType(
        ObjectId $object_id
    ): string {
        return $this->cache[$object_id->toInt()]["type"];
    }

    protected function getCachedFileIdentifier(
        ObjectId $object_id
    ): string {
        return $this->cache[$object_id->toInt()]["file_identifier"];
    }

    protected function removeCache(
        ObjectId $object_id
    ): void {
        unset($this->cache[$object_id->toInt()]);
    }

    protected function isCached(
        ObjectId $object_id
    ): bool {
        return array_key_exists($object_id->toInt(), $this->cache);
    }
}
