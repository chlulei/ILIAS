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
use ILIAS\Export\ExportHandler\I\Info\Export\Container\ilHandlerInterface as ilExportHandlerContainerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Info\Export\ilHandlerInterface as ilExportHandlerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Manager\ilHandlerInterface as ilExportHandlerManagerInterface;
use ILIAS\Export\ExportHandler\I\Manager\ObjectId\ilCollectionInterface as ilExportHandlerManagerObjectIdCollectionInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilHandlerInterface as ilExportHandlerRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\Target\ilHandlerInterface as ilExportHandlerTargetInterface;
use ILIAS\Export\ExportHandler\Info\Export\ilHandler as ilExportHandlerExportInfo;
use ILIAS\Filesystem\Stream\Streams;
use ilImportExportFactory;
use ilObject;

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
        #int $timestamp,
        ilExportHandlerContainerExportInfoInterface $container_export_info
    ): ilExportHandlerRepositoryElementInterface {
        $main_export_info = $container_export_info->getMainEntityExportInfo();
        $main_element = $this->createExport($user_id, $main_export_info, "set_" . $main_export_info->getSetNumber());
        $export_infos = $container_export_info->getExportInfos();
        foreach ($export_infos as $export_info) {
            $element = $export_info->getResueExport()
                ? $this->export_handler->repository()->handler()->getElements($export_info->getTargetObjectId())->newest()
                : $this->createExport($user_id, $export_info, "");
            $zip_reader = new ZipReader($element->getStream());
            $zip_structure = $zip_reader->getStructure();
            foreach ($zip_structure as $path_inside_zip => $item) {
                if ($item['is_dir']) {
                    continue;
                }
                $stream = $zip_reader->getItem($path_inside_zip, $zip_structure)[0];
                $main_element->write($stream, "set_" . $export_info->getSetNumber() . DIRECTORY_SEPARATOR . $path_inside_zip);
            }
            $export_infos = $export_infos->withExportInfo($export_info);
        }
        $container = $this->export_handler->part()->container()->handler()
            ->withExportInfos($export_infos->withExportInfo($main_export_info))
            ->withMainEntityExportInfo($main_export_info);
        $main_element->write(Streams::ofString($container->getXML()), "manifest.xml");
        return $main_element;
    }

    public function createExport(
        int $user_id,
        ilExportHandlerExportInfoInterface $export_info,
        string $path_in_container
    ): ilExportHandlerRepositoryElementInterface {
        $stakeholder = $this->export_handler->repository()->stakeholder()->withOwnerId($user_id);
        $object_id = new ObjectId($export_info->getTarget()->getObjectIds()[0]);
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

    public function getContainerExportInfo(
        ObjectId $main_entity_object_id,
        array $object_ids_to_export,
        array $object_ids_all
    ): ilExportHandlerContainerExportInfoInterface {
        $object_ids = $this->export_handler->manager()->objectId()->collection();
        foreach ($object_ids_all as $object_id) {
            $object_ids = $object_ids->withObjectId(
                $this->export_handler->manager()->objectId()->handler()
                    ->withObjectId(new ObjectId($object_id))
                    ->withReuseExport(!in_array($object_id, $object_ids_to_export))
            );
        }
        return $this->export_handler->info()->export()->container()->handler()
            ->withMainExportEntity($main_entity_object_id)
            ->withObjectIds($object_ids)
            ->withTimestamp(time());
    }
}
