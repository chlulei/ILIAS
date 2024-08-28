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

use ilDBConstants;
use ilDBInterface;
use ilFileUtils;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Info\Export\ilHandlerInterface as ilExportHandlerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilCollectionInterface as ilExportHandlerRepositoryElementCollectionInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilHandlerInterface as ilExportHandlerRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilHandlerInterface as ilExportHandlerRepositoryInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilResourceStakeholderInterface as ilExportHandlerRepositoryResourceStakeholderInterface;
use ILIAS\Filesystem\Filesystems;
use ILIAS\Filesystem\Stream\Streams;
use ILIAS\Filesystem\Util\Archive\Zip;
use ILIAS\Filesystem\Util\Archive\ZipOptions;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;
use SplFileInfo;

class ilHandler implements ilExportHandlerRepositoryInterface
{
    protected ilDBInterface $db;
    protected ilExportHandlerFactoryInterface $export_handler;
    protected ResourcesStorageService $irss;
    protected Filesystems $filesystems;

    public function __construct(
        ResourcesStorageService $irss,
        ilDBInterface $db,
        ilExportHandlerFactoryInterface $export_handler,
        Filesystems $filesystems
    ) {
        $this->irss = $irss;
        $this->db = $db;
        $this->export_handler = $export_handler;
        $this->filesystems = $filesystems;
        #$this->test();
    }

    protected function test()
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
            'rid' => [
                'type' => 'text',
                'length' => 64,
                'default' => '',
                'notnull' => true
            ],
            'owner_id' => [
                'type' => 'integer',
                'length' => 8,
                'default' => 0,
                'notnull' => true
            ],
            'timestamp' => [
                'type' => 'integer',
                'length' => 8,
                'default' => 0,
                'notnull' => true
            ],
        ]);
        $this->db->addPrimaryKey(self::TABLE_NAME, ["object_id", "rid"]);
    }

    protected function createEmptyContainer(
        ilExportHandlerExportInfoInterface $info,
        ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder
    ): ResourceIdentification {
        $tmp_dir_info = new SplFileInfo(ilFileUtils::ilTempnam());
        $this->filesystems->temp()->createDir($tmp_dir_info->getFilename());
        $export_dir = $tmp_dir_info->getRealPath();
        $options = (new ZipOptions())
            ->withZipOutputName($info->getZipFileName())
            ->withZipOutputPath($export_dir);
        $zip = new Zip(
            $options,
        );
        $zip->addStream(Streams::ofString(self::TMP_FILE_CONTENT), self::TMP_FILE_PATH);
        $rid = $this->irss->manageContainer()->containerFromStream($zip->get(), $stakeholder->asAbstractResourceStakeholder());
        ilFileUtils::delDir($export_dir);
        return $rid;
    }

    public function createElement(
        int $object_id,
        ilExportHandlerExportInfoInterface $info,
        ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder
    ): ilExportHandlerRepositoryElementInterface {
        $element = $this->export_handler->repository()->element()->handler()
            ->withObjectId($object_id)
            ->withResourceId($this->createEmptyContainer($info, $stakeholder))
            ->withStakeholder($stakeholder);
        $this->storeElement($element);
        return $element;
    }

    public function storeElement(ilExportHandlerRepositoryElementInterface $element): bool
    {
        if (!$element->isStorable()) {
            return false;
        }
        $query = "INSERT INTO " . $this->db->quoteIdentifier(self::TABLE_NAME) . " VALUES"
            . " (" . $this->db->quote($element->getObjectId(), ilDBConstants::T_INTEGER)
            . ", " . $this->db->quote($element->getResourceId(), ilDBConstants::T_TEXT)
            . ", " . $this->db->quote($element->getStakeholder()->getOwnerId(), ilDBConstants::T_INTEGER)
            . ", " . $this->db->quote($element->getLastModified()->getTimestamp(), ilDBConstants::T_INTEGER)
            . ") ON DUPLICATE KEY UPDATE"
            . " object_id = " . $this->db->quote($element->getObjectId(), ilDBConstants::T_INTEGER)
            . ", rid = " . $this->db->quote($element->getResourceId(), ilDBConstants::T_TEXT)
            . ", owner_id = " . $this->db->quote($element->getStakeholder()->getOwnerId(), ilDBConstants::T_INTEGER)
            . ", timestamp = " . $this->db->quote($element->getLastModified()->getTimestamp(), ilDBConstants::T_INTEGER);
        $this->db->manipulate($query);
        return true;
    }

    public function deleteElement(
        ilExportHandlerRepositoryElementInterface $element,
        ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder
    ): bool {
        return $this->deleteElements(
            $this->export_handler->repository()->element()->collection()->withElement($element),
            $stakeholder
        );
    }

    public function deleteElements(
        ilExportHandlerRepositoryElementCollectionInterface $elements,
        ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder
    ): bool {
        $tuples = [];
        foreach ($elements as $element) {
            if (!$element->isStorable()) {
                return false;
            }
            $tuples[] = "("
                . $this->db->quote($element->getObjectId(), ilDBConstants::T_INTEGER)
                . ","
                . $this->db->quote($element->getResourceId()->serialize(), ilDBConstants::T_TEXT)
                . ")";
        }
        $query = "DELETE FROM " . $this->db->quoteIdentifier(self::TABLE_NAME)
            . " WHERE (object_id, rid) in (" . implode(",", $tuples) . ")";
        $this->db->manipulate($query);
        foreach ($elements as $element) {
            $this->irss->manageContainer()->remove(
                $element->getResourceId(),
                $stakeholder->asAbstractResourceStakeholder()
            );
        }
        return true;
    }

    public function getElement(int $object_id, string $resource_id_serialized): ?ilExportHandlerRepositoryElementInterface
    {
        $query = "SELECT * FROM " . $this->db->quoteIdentifier(self::TABLE_NAME)
            . " WHERE object_id = " . $this->db->quote($object_id, ilDBConstants::T_INTEGER)
            . " AND rid = " . $this->db->quote($resource_id_serialized, ilDBConstants::T_TEXT);
        $res = $this->db->query($query);
        $row = $res->fetchAssoc();
        if (is_null($row)) {
            return null;
        }
        return $this->export_handler->repository()->element()->handler()
            ->withResourceId($this->irss->manageContainer()->find($row["rid"]))
            ->withStakeholder($this->export_handler->repository()->stakeholder()->withOwnerId((int) $row["owner_id"]))
            ->withObjectId((int) $row["object_id"]);
    }

    public function getElements(int $object_id): ilExportHandlerRepositoryElementCollectionInterface
    {
        $collection = $this->export_handler->repository()->element()->collection();
        $query = "SELECT * FROM " . $this->db->quoteIdentifier(self::TABLE_NAME)
            . " WHERE object_id = " . $this->db->quote($object_id, ilDBConstants::T_INTEGER);
        $res = $this->db->query($query);
        while ($row = $res->fetchAssoc()) {
            $rcid = $this->irss->manageContainer()->find($row['rid']);
            $collection = $collection->withElement(
                $this->export_handler->repository()->element()->handler()
                ->withResourceId($rcid)
                ->withObjectId($object_id)
                ->withStakeholder($this->export_handler->repository()->stakeholder()
                    ->withOwnerId((int) $row['owner_id']))
            );
        }
        return $collection;
    }

    public function getElementsByResourceIds(int $object_id, string ...$resource_ids_serialized): ilExportHandlerRepositoryElementCollectionInterface
    {
        $collection = $this->export_handler->repository()->element()->collection();
        $tuples = [];
        foreach ($resource_ids_serialized as $resource_id_serialized) {
            $tuples[] = "("
                . $this->db->quote($object_id, ilDBConstants::T_INTEGER)
                . ","
                . $this->db->quote($resource_id_serialized, ilDBConstants::T_TEXT)
                . ")";
        }
        $query = "SELECT * FROM " . $this->db->quoteIdentifier(self::TABLE_NAME)
            . " WHERE (object_id, rid) in (" . implode(",", $tuples) . ")";
        $res = $this->db->query($query);
        while ($row = $res->fetchAssoc()) {
            $collection = $collection->withElement(
                $this->export_handler->repository()->element()->handler()
                    ->withResourceId($this->irss->manageContainer()->find($row['rid']))
                    ->withObjectId((int) $row['object_id'])
                    ->withStakeholder($this->export_handler->repository()->stakeholder()->withOwnerId((int) $row['owner_id']))
            );
        }
        return $collection;
    }
}
