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

namespace ILIAS\Export\ExportTable;

use ilExportGUI;
use ILIAS\Export\ExportHandler\I\Info\File\ilHandlerInterface as ilExportHandlerFileInfoInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilCollectionInterface as ilExportHandlerConsumerExportOptionCollectionInterface;
use ILIAS\UI\Component\Table\DataRetrieval as ilTableDataRetrievalInterface;
use ILIAS\UI\Component\Table\DataRowBuilder as ilTableDataRowBuilderInterface;
use ILIAS\Data\Range as ilDataRange;
use ILIAS\Data\Order as ilDataOrder;
use ILIAS\UI\Factory as ilUIFactoryInterface;
use ILIAS\DI\UIServices as ilUIServices;
use ILIAS\UI\Renderer as ilUIRendererInterface;
use ILIAS\Export\ExportHandler\ilFactory as ilExportHandler;
use Generator;
use ilObject;
use SplFileInfo;

class ilDataRetreival implements ilTableDataRetrievalInterface
{
    protected ilUIServices $ui_services;
    protected ilExportHandler $export_handler;
    protected ilExportGUI $export_gui;
    protected ilObject $export_object;
    protected ilExportHandlerConsumerExportOptionCollectionInterface $export_options;

    public function __construct(
        ilUIServices $ui_services,
        ilExportHandler $export_handler,
        ilExportGUI $export_gui,
        ilObject $export_object,
        ilExportHandlerConsumerExportOptionCollectionInterface $export_options
    ) {
        $this->ui_services = $ui_services;
        $this->export_handler = $export_handler;
        $this->export_gui = $export_gui;
        $this->export_object = $export_object;
        $this->export_options = $export_options;
    }

    public function getRows(
        ilTableDataRowBuilderInterface $row_builder,
        array $visible_column_ids,
        ilDataRange $range,
        ilDataOrder $order,
        ?array $filter_data,
        ?array $additional_parameters
    ): Generator {
        $icons = [
            $this->ui_services->factory()->symbol()->icon()->custom('assets/images/standard/icon_checked.svg', '', 'small'),
            $this->ui_services->factory()->symbol()->icon()->custom('assets/images/standard/icon_unchecked.svg', '', 'small')
        ];
        $context = $this->export_handler->consumer()->context()->handler($this->export_gui, $this->export_object);
        for ($i = 0; $i < $this->export_options->count(); $i++) {
            foreach ($this->export_options->getIdFileInfoPairs($i, $context) as $id => $file_info) {
                yield $row_builder->buildDataRow($id, [
                    "type" => $file_info->getFileType(),
                    "file" => $file_info->getFileName(),
                    "size" => $file_info->getFileSize(),
                    "timestamp" => $file_info->getLastChanged(),
                    "public_access" => $file_info->getPublicAccessEnabled() ? $icons[0] : $icons[1]
                ]);
            }
        }
    }

    public function getTotalRowCount(
        ?array $filter_data,
        ?array $additional_parameters
    ): ?int {
        return  $this->export_handler->repository()->handler()->getElements($this->export_object->getId())->count();
    }
}
