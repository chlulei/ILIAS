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

namespace ILIAS\Export\ExportHandler\PublicAccess\Repository;

use ilDBConstants;
use ilDBInterface;
use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessRepositoryElementCollectionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\ilHandlerInterface as ilExportHandlerPublicAccessRepositoryInterface;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;

class ilHandler implements ilExportHandlerPublicAccessRepositoryInterface
{
    protected const TABLE_NAME = "export_public_access";

    protected ilExportHandlerFactoryInterface $export_handler;
    protected ilDBInterface $db;
    protected ResourcesStorageService $irss;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler,
        ResourcesStorageService $irss,
        ilDBInterface $db
    ) {
        $this->export_handler = $export_handler;
        $this->irss = $irss;
        $this->db = $db;
        $this->test();
    }

    protected function test()
    {
        if ($this->db->tableExists(self::TABLE_NAME)) {
            return;
        }
        $this->db->createTable(self::TABLE_NAME, [
            'reference_id' => [
                'type' => 'integer',
                'length' => 8,
                'default' => 0,
                'notnull' => true
            ],
            'rid' => [
                'type' => 'text',
                'length' => 64,
                'default' => '',
                'notnull' => true
            ],
            'timestamp' => [
                'type' => 'integer',
                'length' => 8,
                'default' => 0,
                'notnull' => true
            ],
        ]);
        $this->db->addPrimaryKey(self::TABLE_NAME, ["reference_id"]);
    }

    public function storeElement(ilExportHandlerPublicAccessRepositoryElementInterface $element): bool
    {
        if (!$element->isStorable()) {
            return false;
        }
        $query = "INSERT INTO " . $this->db->quoteIdentifier(self::TABLE_NAME) . " VALUES"
            . " (" . $this->db->quote($element->getReferenceId()->toInt(), ilDBConstants::T_INTEGER)
            . ", " . $this->db->quote($element->getIdentification(), ilDBConstants::T_TEXT)
            . ", " . $this->db->quote($element->getLastModified()->getTimestamp(), ilDBConstants::T_INTEGER)
            . ") ON DUPLICATE KEY UPDATE"
            . " reference_id = " . $this->db->quote($element->getReferenceId()->toInt(), ilDBConstants::T_INTEGER)
            . ", rid = " . $this->db->quote($element->getIdentification(), ilDBConstants::T_TEXT)
            . ", timestamp = " . $this->db->quote($element->getLastModified()->getTimestamp(), ilDBConstants::T_INTEGER);
        $this->db->manipulate($query);
        $this->export_handler->publicAccess()->link()->handler()->withReferenceId(
            $element->getReferenceId()
        );
        return true;
    }

    public function getElement(ReferenceId $reference_id): ilExportHandlerPublicAccessRepositoryElementInterface
    {
        $query = "SELECT * FROM " . $this->db->quoteIdentifier(self::TABLE_NAME)
            . " WHERE reference_id = " . $this->db->quote($reference_id->toInt(), ilDBConstants::T_INTEGER);
        $res = $this->db->query($query);
        $row = $res->fetchAssoc();
        if (is_null($row)) {
            return $this->export_handler->publicAccess()->repository()->element()->handler();
        }
        $rcid = $this->irss->manageContainer()->find($row['rid']);
        return $this->export_handler->publicAccess()->repository()->element()->handler()
            ->withReferenceId($reference_id)
            ->withIdentification($rcid->serialize());
    }

    public function hasElement(ilExportHandlerPublicAccessRepositoryElementInterface $element): bool
    {
        $found_element = $this->getElement($element->getReferenceId());
        return $found_element->isStorable() and $found_element->getIdentification() === $element->getIdentification();
    }

    public function getElements(): ilExportHandlerPublicAccessRepositoryElementCollectionInterface
    {
        $collection = $this->export_handler->publicAccess()->repository()->element()->collection();
        $query = "SELECT * FROM " . $this->db->quoteIdentifier(self::TABLE_NAME);
        $res = $this->db->query($query);
        while ($row = $res->fetchAssoc()) {
            $rcid = $this->irss->manageContainer()->find($row['rid']);
            $reference_id = (int) $row['reference_id'];
            $collection = $collection->withElement(
                $this->export_handler->publicAccess()->repository()->element()->handler()
                    ->withIdentification($rcid->serialize())
                    ->withReferenceId(new ReferenceId($reference_id))
            );
        }
        return $collection;
    }

    public function deleteElement(ilExportHandlerPublicAccessRepositoryElementInterface $element): bool
    {
        if (!$element->isStorable()) {
            return false;
        }
        $query = "DELETE FROM " . $this->db->quoteIdentifier(self::TABLE_NAME) . " WHERE "
            . "reference_id = " . $this->db->quote($element->getReferenceId()->toInt(), ilDBConstants::T_INTEGER);
        $this->db->manipulate($query);
        return true;
    }

    public function deleteElements(ilExportHandlerPublicAccessRepositoryElementCollectionInterface $elements): bool
    {
        $reference_ids = [];
        foreach ($elements as $element) {
            if (!$element->isStorable()) {
                return false;
            }
            $reference_ids[] = $element->getReferenceId()->toInt();
        }
        $query = "DELETE FROM " . $this->db->quoteIdentifier(self::TABLE_NAME)
            . " WHERE " . $this->db->in("reference_id", $reference_ids, false, ilDBConstants::T_INTEGER);
        $this->db->manipulate($query);
        return true;
    }
}
