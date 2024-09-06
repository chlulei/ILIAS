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

namespace ILIAS\Export\ExportHandler\PublicAccess\Restriction;

use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\ilHandlerInterface as ilExportHandlerPublicAccessRestrictionInterface;

class ilHandler implements ilExportHandlerPublicAccessRestrictionInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;
    protected array $cache;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler
    ) {
        $this->export_handler = $export_handler;
        $this->cache = [];
    }

    public function enablePublicAccessForExportOption(ObjectId $object_id, string $export_option_id): bool
    {
        $repository = $this->export_handler->publicAccess()->restriction()->repository();
        $success = $repository->handler()->addElement(
            $repository->element()->handler()
                ->withObjectId($object_id)
                ->withExportOptionId($export_option_id)
        );
        if ($success) {
            $this->updateCache(
                $object_id,
                $repository->handler()->getElements($object_id)->types()
            );
        }
        return $success;
    }

    public function disablePublicAccessForExportOption(ObjectId $object_id, string $export_option_id): bool
    {
        $repository = $this->export_handler->publicAccess()->restriction()->repository();
        $success = $repository->handler()->removeElement(
            $repository->element()->handler()
                ->withObjectId($object_id)
                ->withExportOptionId($export_option_id)
        );
        if ($success) {
            $this->updateCache(
                $object_id,
                $repository->handler()->getElements($object_id)->types()
            );
        }
        return $success;
    }

    public function isPublicAccessForExportOptionAllowed(ObjectId $object_id, string $export_option_id): bool
    {
        $repository = $this->export_handler->publicAccess()->restriction()->repository();
        if (!$this->isCached($object_id)) {
            $this->updateCache(
                $object_id,
                $repository->handler()->getElements($object_id)->types()
            );
        }
        return $this->isCachedType($object_id, $export_option_id);
    }

    protected function updateCache(
        ObjectId $object_id,
        array $types
    ): void {
        $this->cache[$object_id->toInt()]["types"] = $types;
    }

    protected function isCachedType(
        ObjectId $object_id,
        string $type
    ): bool {
        return in_array($type, $this->cache[$object_id->toInt()]["types"] ?? []);
    }

    protected function isCached(
        ObjectId $object_id
    ): bool {
        return array_key_exists($object_id->toInt(), $this->cache);
    }
}
