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

namespace ILIAS\Export\ExportHandler\Manager;

use ILIAS\components\ResourceStorage\Container\Wrapper\ZipReader;
use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Info\Export\ilHandlerInterface as ilExportHandlerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Manager\ilHandlerInterface as ilExportHandlerManagerInterface;
use ILIAS\Export\ExportHandler\I\Manager\ObjectId\ilCollectionInterface as ilExportHandlerManagerObjectIdCollectionInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilHandlerInterface as ilExportHandlerRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilResourceStakeholderInterface as ilExportHandlerResourceStakeholderInterface;
use ILIAS\Export\ExportHandler\I\Target\ilHandlerInterface as ilExportHandlerTargetInterface;
use ILIAS\Export\ExportHandler\Info\Export\ilHandler as ilExportHandlerExportInfo;
use ILIAS\Filesystem\Stream\Streams;
use ilImportExportFactory;
use ilObject;
use ilObjUser;

class ilHandler implements ilExportHandlerManagerInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler
    ) {
        $this->export_handler = $export_handler;
    }

    protected function getExportTarget(
        ObjectId $object_id
    ): ilExportHandlerTargetInterface {
        $obj_id = $object_id->toInt();
        $type = ilObject::_lookupType($obj_id);
        $class = ilImportExportFactory::getExporterClass($type);
        $comp = ilImportExportFactory::getComponentForExport($type);
        return $this->export_handler->target()->handler()
            ->withTargetRelease("")
            ->withType($type)
            ->withObjectIds([$obj_id])
            ->withClassname($class)
            ->withComponent($comp);
    }

    protected function writeToElement(
        string $path_in_container,
        ilExportHandlerExportInfo $export_info,
        ilExportHandlerRepositoryElementInterface $element
    ): void {
        $manifest = $this->export_handler->part()->manifest()->handler()
            ->withInfo($export_info);
        $element->write(Streams::ofString($manifest->getXML()), $path_in_container . DIRECTORY_SEPARATOR . $export_info->getExportFolderName() . DIRECTORY_SEPARATOR . "manifest.xml");
        foreach ($export_info->getComponentInfos() as $component_info) {
            $component = $this->export_handler->part()->component()->handler()
                ->withExportInfo($export_info)
                ->withComponentInfo($component_info);
            $element->write(Streams::ofString($component->getXML()), $path_in_container . DIRECTORY_SEPARATOR . $component_info->getPathInContainer());
        }
    }

    public function createContainerExport(
        int $user_id,
        int $timestamp,
        ObjectId $main_entity_object_id,
        ilExportHandlerManagerObjectIdCollectionInterface $obj_id_collection
    ): ilExportHandlerRepositoryElementInterface {
        $set_id = 1;
        $main_entity_export_info = $this->getExportInfo(
            $main_entity_object_id,
            $timestamp
        )->withSetNumber($set_id);
        $main_element = $this->createExport(
            $main_entity_object_id,
            $user_id,
            $timestamp,
            "set_" . $set_id++
        );
        $export_infos = $this->export_handler->info()->export()->collection()->withExportInfo($main_entity_export_info);
        foreach ($obj_id_collection as $obj_id_handler) {
            $obj_id = $obj_id_handler->getObjectId();
            $element = null;
            $export_info = null;
            if ($obj_id_handler->getReuseExport()) {
                $element = $this->export_handler->repository()->handler()->getElements($obj_id)->newest();
                $export_info = $this->getExportInfo($obj_id, $element->getLastModified()->getTimestamp())->withSetNumber($set_id);
            }
            if (!$obj_id_handler->getReuseExport()) {
                $element = $this->createExport($obj_id, $user_id, $timestamp, "");
                $export_info = $this->getExportInfo($obj_id, $timestamp)->withSetNumber($set_id);
            }
            $zip_reader = new ZipReader($element->getStream());
            $zip_structure = $zip_reader->getStructure();
            foreach ($zip_structure as $path_inside_zip => $item) {
                if ($item['is_dir']) {
                    continue;
                }
                $stream = $zip_reader->getItem($path_inside_zip, $zip_structure)[0];
                $main_element->write($stream, "set_" . $set_id . DIRECTORY_SEPARATOR . $path_inside_zip);
            }
            $export_infos = $export_infos->withExportInfo($export_info);
            $set_id++;
        }
        $container = $this->export_handler->part()->container()->handler()
            ->withExportInfos($export_infos)
            ->withMainEntityExportInfo($main_entity_export_info);
        $main_element->write(Streams::ofString($container->getContainerManifestXML()), "manifest.xml");
        return $main_element;
    }

    public function createExport(
        ObjectId $object_id,
        int $user_id,
        int $timestamp,
        string $path_in_container
    ): ilExportHandlerRepositoryElementInterface {
        $stakeholder = $this->export_handler->repository()->stakeholder()->withOwnerId($user_id);
        $export_info = $this->getExportInfo($object_id, $timestamp);
        $element = $this->export_handler->repository()->handler()->createElement(
            $object_id,
            $export_info,
            $stakeholder
        );
        $this->writeToElement($path_in_container, $export_info, $element);
        return $element;
    }

    public function getExportInfo(
        ObjectId $object_id,
        int $time_stamp
    ): ilExportHandlerExportInfoInterface {
        return $this->export_handler->info()->export()->handler()
            ->withTarget($this->getExportTarget($object_id), $time_stamp);
    }

    public function createObjectIdCollection(
        array $object_ids_to_export,
        array $object_ids_all
    ): ilExportHandlerManagerObjectIdCollectionInterface {
        $object_ids = $this->export_handler->manager()->objectId()->collection();
        foreach ($object_ids_all as $object_id) {
            $object_ids = $object_ids->withObjectId(
                $this->export_handler->manager()->objectId()->handler()
                    ->withObjectId(new ObjectId($object_id))
                    ->withReuseExport(!in_array($object_id, $object_ids_to_export))
            );
        }
        return $object_ids;
    }
}
