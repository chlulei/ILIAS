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

use ILIAS\Data\ObjectId;
use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\Consumer\ExportOption\BasicLegacyHandler as ilBasicLegacyExportOption;
use ILIAS\Export\ExportHandler\I\Consumer\Context\HandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\Identifier\CollectionInterface as ilExportHandlerConsumerFileIdentifierCollectionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\Identifier\HandlerInterface as ilExportHandlerConsumerFileIdentifierInterface;
use ILIAS\Export\ExportHandler\I\Info\File\CollectionInterface as ilExportHandlerFileInfoCollectionInterface;
use ILIAS\DI\Container;

class ilMediaPoolExportOptionXMLMasterNoMedia extends ilBasicLegacyExportOption
{
    protected ilLanguage $lng;

    public function init(Container $DIC): void
    {
        $this->lng = $DIC->language();
        parent::init($DIC);
    }

    public function getExportType(): string
    {
        return 'xml_masternomedia';
    }

    public function getExportOptionId(): string
    {
        return 'ilMediaPoolExportOptionXMLMasterNoMedia';
    }

    public function getSupportedRepositoryObjectTypes(): array
    {
        return ['mep'];
    }

    public function getLabel(): string
    {
        return "XML (" . $this->lng->txt("mep_master_language_only_no_media") . ")";
    }

    public function onDownloadFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerConsumerFileIdentifierCollectionInterface $file_identifiers
    ): void {
        foreach ($file_identifiers as $file_identifier) {
            $file = explode(":", trim($file_identifier->getIdentifier()));
            $export_dir = ilExport::_getExportDirectory(
                $context->exportObject()->getId(),
                "",
                $context->exportObject()->getType()
            );
            $export_dir = substr($export_dir, 0, strlen($export_dir) - 1);
            $file[1] = basename($file[1]);
            ilFileDelivery::deliverFileLegacy(
                $export_dir . "/" . $file[1],
                $file[1]
            );
        }
    }

    public function onDownloadWithLink(
        ReferenceId $reference_id,
        ilExportHandlerConsumerFileIdentifierInterface $file_identifier
    ): void {
        $object_id = $reference_id->toObjectId()->toInt();
        $type = ilObject::_lookupType($object_id);
        $file = explode(":", trim($file_identifier->getIdentifier()));
        $export_dir = ilExport::_getExportDirectory(
            $object_id,
            "",
            $type
        );
        $export_dir = substr($export_dir, 0, strlen($export_dir) - 1);
        $file[1] = basename($file[1]);
        ilFileDelivery::deliverFileLegacy(
            $export_dir . "/" . $file[1],
            $file[1]
        );
    }

    public function onDeleteFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerConsumerFileIdentifierCollectionInterface $file_identifiers
    ): void {
        foreach ($file_identifiers as $file_identifier) {
            $file = explode(":", $file_identifier->getIdentifier());

            $file[1] = basename($file[1]);

            $export_dir = ilExport::_getExportDirectory(
                $context->exportObject()->getId(),
                "",
                $context->exportObject()->getType()
            );
            $export_dir = substr($export_dir, 0, strlen($export_dir) - 1);

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
    }

    public function getFiles(
        ilExportHandlerConsumerContextInterface $context
    ): ilExportHandlerFileInfoCollectionInterface {
        # The export files of ilMediaPoolExportOptionXMLMaster and ilMediaPoolExportOptionXMLMasterNoMedia
        # cannot be differentiated, therefore only ilMediaPoolExportOptionXMLMaster returns files.
        return $context->fileCollectionBuilder()->collection();
    }

    public function isObjectSupported(ObjectId $object_id): bool
    {
        $ot = ilObjectTranslation::getInstance($object_id->toInt());
        return $ot->getContentActivated();
    }

    public function onExportOptionSelected(ilExportHandlerConsumerContextInterface $context): void
    {
        if ($this->isObjectSupported(new ObjectId($context->exportObject()->getId()))) {
            $opt = ilUtil::stripSlashes("masternomedia");
            $context->exportObject()->exportXML($opt);
        }
        $this->ctrl->redirectByClass(ilExportGUI::class, ilExportGUI::CMD_LIST_EXPORT_FILES);
    }
}
