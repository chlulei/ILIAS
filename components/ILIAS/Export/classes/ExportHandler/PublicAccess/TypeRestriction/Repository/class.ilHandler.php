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

namespace ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\Repository;

use ilDBConstants;
use ilDBInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessTypeRestrictionElementCollectionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\ilHandlerInterface as ilExportHandlerPublicAccessTypeRestrictionRepositoryInterface;

class ilHandler implements ilExportHandlerPublicAccessTypeRestrictionRepositoryInterface
{
    protected const TABLE_NAME = "export_pub_acc_types";

    protected ilExportHandlerFactoryInterface $export_handler;
    protected ilDBInterface $db;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler,
        ilDBInterface $db
    ) {
        $this->export_handler = $export_handler;
        $this->db = $db;
        $this->test();
    }

    protected function test(): void
    {
        if ($this->db->tableExists(self::TABLE_NAME)) {
            return;
        }
        $this->db->createTable(self::TABLE_NAME, [
            'object_id' => [
                'type' => 'integer',
                'length' => 8,
                'default' => 0,
                'notnull' => true
            ],
            'type' => [
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
        $this->db->addPrimaryKey(self::TABLE_NAME, ["object_id", "type"]);
    }

    public function addAllowedType(ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface $element): bool
    {
        if (!$element->isStorable()) {
            return false;
        }
        $query = "INSERT INTO " . $this->db->quoteIdentifier(self::TABLE_NAME) . " VALUES"
            . " (" . $this->db->quote($element->getObjectId(), ilDBConstants::T_INTEGER)
            . ", " . $this->db->quote($element->getAllowedType(), ilDBConstants::T_TEXT)
            . ", " . $this->db->quote($element->getLastModified()->getTimestamp(), ilDBConstants::T_INTEGER)
            . ") ON DUPLICATE KEY UPDATE"
            . " object_id = " . $this->db->quote($element->getObjectId(), ilDBConstants::T_INTEGER)
            . ", type = " . $this->db->quote($element->getAllowedType(), ilDBConstants::T_TEXT)
            . ", timestamp = " . $this->db->quote($element->getLastModified()->getTimestamp(), ilDBConstants::T_INTEGER);
        $this->db->manipulate($query);
        return true;
    }

    public function removeAllowedType(ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface $element): bool
    {
        if (!$element->isStorable()) {
            return false;
        }
        $query = "DELETE FROM " . $this->db->quoteIdentifier(self::TABLE_NAME) . " WHERE "
            . "(object_id, type) = (" . $this->db->quote($element->getObjectId(), ilDBConstants::T_INTEGER)
            . ", " . $this->db->quote($element->getAllowedType(), ilDBConstants::T_TEXT) . ")";
        $this->db->manipulate($query);
        return true;

    }

    public function getAllowedTypes(int $object_id): ilExportHandlerPublicAccessTypeRestrictionElementCollectionInterface
    {
        $collection = $this->export_handler->publicAccess()->typeRestriction()->repository()->element()->collection();
        $query = "SELECT * FROM " . $this->db->quoteIdentifier(self::TABLE_NAME)
            . " WHERE object_id = " . $this->db->quote($object_id, ilDBConstants::T_INTEGER);
        $res = $this->db->query($query);
        while ($row = $res->fetchAssoc()) {
            $collection = $collection->withElement(
                $this->export_handler->publicAccess()->typeRestriction()->repository()->element()->handler()
                    ->withObjectId((int) $row['object_id'])
                    ->withAllowedType($row['type'])
            );
        }
        return $collection;
    }

    public function isTypeAllowed(ilExportHandlerPublicAccessTypeRestrictionRepositoryElementInterface $element): bool
    {
        if (!$element->isStorable()) {
            return false;
        }
        foreach ($this->getAllowedTypes($element->getObjectId()) as $type) {
            if ($type->getAllowedType() === $element->getAllowedType()) {
                return true;
            }
        }
        return false;
    }
}
