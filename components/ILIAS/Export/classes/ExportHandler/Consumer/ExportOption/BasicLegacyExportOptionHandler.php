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

namespace ILIAS\Export\ExportHandler\Consumer\ExportOption;

use ilExport;
use ilExportFileInfo;
use ilFileDelivery;
use ilFileUtils;
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilHandlerInterface as ilExportHandlerConsumerExportOptionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\ilCollectionInterface as ilExportHandlerConusmerFileCollectionInterface;
use ILIAS\Export\ExportHandler\I\Table\RowId\ilCollectionInterface as ilExportHandlerTableRowIdCollectionInterface;
use SplFileInfo;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessTypeRestrictionRepitoryElementCollectionInterface;

abstract class BasicLegacyExportOptionHandler implements ilExportHandlerConsumerExportOptionInterface
{
    public function onPublicAccessTypeRestrictionsChanged(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerPublicAccessTypeRestrictionRepitoryElementCollectionInterface $allowed_types
    ): void {
        $is_allowed_type = false;
        foreach ($allowed_types as $allowed_type) {
            if ($allowed_type->getAllowedType() === $this->getExportType()) {
                $is_allowed_type = true;
                break;
            }
        }
        if(!$is_allowed_type) {
            $context->publicAccess()->removePublicAccessFile($context->exportObject()->getId());
        }
    }

    public function onDeleteFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): void {
        foreach ($table_row_ids as $table_row_id) {
            $file = explode(":", $table_row_id->getCompositId());

            $file[1] = basename($file[1]);

            $export_dir = ilExport::_getExportDirectory(
                $context->exportObject()->getId(),
                str_replace("..", "", $file[0]),
                $context->exportObject()->getType()
            );

            $exp_file = $export_dir . "/" . str_replace("..", "", $file[1]);
            $exp_dir = $export_dir . "/" . substr($file[1], 0, strlen($file[1]) - 4);
            if (is_file($exp_file)) {
                unlink($exp_file);
            }
            if (is_dir($exp_dir)) {
                ilFileUtils::delDir($exp_dir);
            }

            // delete entry in database
            $info = new ilExportFileInfo($context->exportObject()->getId(), $file[0], $file[1]);
            $info->delete();
        }
        $context->ilCtrl()->redirect($context->exportGUIObject(), "listExportFiles");
    }

    public function onDownloadFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): void {
        foreach ($table_row_ids as $table_row_id) {
            $file = explode(":", trim($table_row_id->getCompositId()));
            $export_dir = ilExport::_getExportDirectory(
                $context->exportObject()->getId(),
                str_replace("..", "", $file[0]),
                $context->exportObject()->getType()
            );
            $file[1] = basename($file[1]);
            ilFileDelivery::deliverFileLegacy(
                $export_dir . "/" . $file[1],
                $file[1]
            );
        }
    }

    public function getFileSelection(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): ilExportHandlerConusmerFileCollectionInterface {
        $collection = $context->fileFactory()->collection();
        foreach ($this->getFiles($context) as $file) {
            foreach ($table_row_ids as $table_row_id) {
                if ($table_row_id->getFileIdentifier() === $file->getFileIdentifier()) {
                    $collection = $collection->addFileInfo($file);
                    break;
                }
            }
        }
        return $collection;
    }

    public function getFiles(
        ilExportHandlerConsumerContextInterface $context
    ): ilExportHandlerConusmerFileCollectionInterface {
        $collection = $context->fileFactory()->collection();
        $dir = ilExport::_getExportDirectory(
            $context->exportObject()->getId(),
            $this->getExportType(),
            $context->exportObject()->getType()
        );
        $file_infos = ilExport::_getExportFiles(
            $context->exportObject()->getId(),
            [$this->getExportType()],
            $context->exportObject()->getType()
        );
        foreach ($file_infos as $file_name => $file_info) {
            $collection = $collection->addFileInfo(
                $context->fileFactory()
                ->fileInfoFromSplFileInfo(
                    new SplFileInfo($dir . DIRECTORY_SEPARATOR . $file_name),
                    $file_info["type"],
                    false
                )->withPublicAccessEnabled(false)
            );
        }
        return $collection;
    }
}
