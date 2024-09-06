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

namespace ILIAS\Export\ExportHandler\PublicAccess\Restriction\Repository;

use ilDBConstants;
use ilDBInterface;
use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessRestrictionElementCollectionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\Repository\Element\ilHandlerInterface as ilExportHandlerPublicAccessRestrictionRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Restriction\Repository\ilHandlerInterface as ilExportHandlerPublicAccessRestrictionRepositoryInterface;

class ilHandler implements ilExportHandlerPublicAccessRestrictionRepositoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;
    protected ilDBInterface $db;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler,
        ilDBInterface $db
    ) {
        $this->export_handler = $export_handler;
        $this->db = $db;
    }

    public function addElement(ilExportHandlerPublicAccessRestrictionRepositoryElementInterface $element): bool
    {
        if (!$element->isStorable()) {
            return false;
        }
        $query = "INSERT INTO " . $this->db->quoteIdentifier(self::TABLE_NAME) . " VALUES"
            . " (" . $this->db->quote($element->getObjectId()->toInt(), ilDBConstants::T_INTEGER)
            . ", " . $this->db->quote($element->getExportOptionId(), ilDBConstants::T_TEXT)
            . ", " . $this->db->quote($element->getLastModified()->format("Y-m-d-H-i-s"), ilDBConstants::T_TIMESTAMP)
            . ") ON DUPLICATE KEY UPDATE"
            . " object_id = " . $this->db->quote($element->getObjectId()->toInt(), ilDBConstants::T_INTEGER)
            . ", export_option_id = " . $this->db->quote($element->getExportOptionId(), ilDBConstants::T_TEXT)
            . ", timestamp = " . $this->db->quote($element->getLastModified()->format("Y-m-d-H-i-s"), ilDBConstants::T_TIMESTAMP);
        $this->db->manipulate($query);
        return true;
    }

    public function removeElement(ilExportHandlerPublicAccessRestrictionRepositoryElementInterface $element): bool
    {
        if (!$element->isStorable()) {
            return false;
        }
        $query = "DELETE FROM " . $this->db->quoteIdentifier(self::TABLE_NAME) . " WHERE "
            . "(object_id, export_option_id) = (" . $this->db->quote($element->getObjectId()->toInt(), ilDBConstants::T_INTEGER)
            . ", " . $this->db->quote($element->getExportOptionId(), ilDBConstants::T_TEXT) . ")";
        $this->db->manipulate($query);
        return true;
    }

    public function getElements(ObjectId $object_id): ilExportHandlerPublicAccessRestrictionElementCollectionInterface
    {
        $collection = $this->export_handler->publicAccess()->restriction()->repository()->element()->collection();
        $query = "SELECT * FROM " . $this->db->quoteIdentifier(self::TABLE_NAME)
            . " WHERE object_id = " . $this->db->quote($object_id->toInt(), ilDBConstants::T_INTEGER);
        $res = $this->db->query($query);
        while ($row = $res->fetchAssoc()) {
            $collection = $collection->withElement(
                $this->export_handler->publicAccess()->restriction()->repository()->element()->handler()
                    ->withObjectId(new ObjectId((int) $row['object_id']))
                    ->withExportOptionId($row['export_option_id'])
            );
        }
        return $collection;
    }

    public function hasElement(ilExportHandlerPublicAccessRestrictionRepositoryElementInterface $element): bool
    {
        if (!$element->isStorable()) {
            return false;
        }
        foreach ($this->getElements($element->getObjectId()) as $col_element) {
            if (
                $col_element->getExportOptionId() === $element->getExportOptionId() and
                $col_element->getObjectId()->toInt() === $element->getObjectId()->toInt()
            ) {
                return true;
            }
        }
        return false;
    }
}
