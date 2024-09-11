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

namespace ILIAS\Export\ExportHandler\Repository;

use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\Info\Export\ilHandlerInterface as ilExportHandlerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilCollectionInterface as ilExportHandlerRepositoryElementCollectionInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilFactoryInterface as ilExportHandlerRepositoryElementFactoryInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilHandlerInterface as ilExportHandlerRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilHandlerInterface as ilExportHandlerRepositoryInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilResourceStakeholderInterface as ilExportHandlerRepositoryResourceStakeholderInterface;
use ILIAS\Export\ExportHandler\I\Repository\Key\ilCollectionInterface as ilExportHandlerRepositoryKeyCollectionInterface;
use ILIAS\Export\ExportHandler\I\Repository\Key\ilFactoryInterface as ilExportHandlerRepositoryKeyFactoryInterface;
use ILIAS\Export\ExportHandler\I\Repository\Wrapper\DB\ilHandlerInterface as ilExportHandlerRepositoryDBWrapperInterface;
use ILIAS\Export\ExportHandler\I\Repository\Wrapper\IRSS\ilHandlerInterface as ilExportHandlerRepositoryIRSSWrapperInterface;

class ilHandler implements ilExportHandlerRepositoryInterface
{
    protected ilExportHandlerRepositoryKeyFactoryInterface $key_factory;
    protected ilExportHandlerRepositoryElementFactoryInterface $element_factory;
    protected ilExportHandlerRepositoryDBWrapperInterface $db_wrapper;
    protected ilExportHandlerRepositoryIRSSWrapperInterface $irss_wrapper;

    public function __construct(
        ilExportHandlerRepositoryKeyFactoryInterface $key_factory,
        ilExportHandlerRepositoryElementFactoryInterface $element_factory,
        ilExportHandlerRepositoryDBWrapperInterface $db_wrapper,
        ilExportHandlerRepositoryIRSSWrapperInterface $irss_wrapper
    ) {
        $this->element_factory = $element_factory;
        $this->db_wrapper = $db_wrapper;
        $this->irss_wrapper = $irss_wrapper;
    }

    public function createElement(
        ObjectId $object_id,
        ilExportHandlerExportInfoInterface $info,
        ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder
    ): ilExportHandlerRepositoryElementInterface {
        $element = $this->element_factory->handler()
            ->withObjectId($object_id)
            ->withResourceIdSerialized($this->irss_wrapper->createEmptyContainer($info, $stakeholder)->serialize())
            ->withOwnerId($stakeholder->getOwnerId());
        $this->storeElement($element);
        return $element;
    }

    public function storeElement(ilExportHandlerRepositoryElementInterface $element): void
    {
        if ($element->isStorable()) {
            $this->db_wrapper->store($element);
        }
    }

    public function deleteElements(
        ilExportHandlerRepositoryKeyCollectionInterface $keys,
        ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder
    ): void {
        $removed_keys = $this->key_factory->collection();
        foreach ($keys as $key) {
            if ($this->irss_wrapper->removeContainer($key->getResourceId(), $stakeholder)) {
                $removed_keys = $removed_keys->withElement($key);
            }
        }
        $this->db_wrapper->deleteElements($removed_keys);
    }

    public function getElements(
        ilExportHandlerRepositoryKeyCollectionInterface $keys
    ): ilExportHandlerRepositoryElementCollectionInterface {
        return $this->db_wrapper->getElements($keys);
    }
}
