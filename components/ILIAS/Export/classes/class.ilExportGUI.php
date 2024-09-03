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
use ILIAS\DI\UIServices as ilUIServices;
use ILIAS\HTTP\Services as ilHTTPServices;
use ILIAS\Refinery\Factory as Factory;
use ILIAS\HTTP\Services as Services;
use ILIAS\Export\ExportHandler\ilFactory as ilExportHandler;
use ILIAS\Refinery\Factory as ilRefineryFactory;
use ILIAS\UI\Component\Button\Button as ilButton;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilCollectionInterface as ilExportHandlerConsumerExportOptionCollectionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilHandlerInterface as ilExportHandlerConsumerExportOptionInterface;

/**
 * Export User Interface Class
 * @author       Alexander Killing <killing@leifos.de>
 * @ilCtrl_Calls ilExportGUI:
 */
class ilExportGUI
{
    public const CMD_LIST_EXPORT_FILES = "listExportFiles";
    public const CMD_EXPORT_XML = "createXmlExportFile";
    protected const CMD_SAVE_ITEM_SELECTION = "saveItemSelection";
    protected const CMD_EXPORT_OPTION_PREFIX = "exportOption";

    protected ilExportHandlerConsumerExportOptionCollectionInterface $export_options;
    protected ilUIServices $ui_services;
    protected ilHTTPServices $http_services;
    protected ilRefineryFactory $refinery;
    protected ilObjUser $il_user;
    protected ilLanguage $lng;
    protected \ILIAS\Export\InternalGUIService $gui;
    protected ilObject $obj;
    protected ilGlobalTemplateInterface $tpl;
    protected ilCtrlInterface $ctrl;
    protected ilAccessHandler $access;
    protected ilErrorHandling $error;
    protected ilToolbarGUI $toolbar;
    protected ilObjectDefinition $objDefinition;
    protected ilTree $tree;
    protected ilExportHandler $export_handler;
    protected array $formats = [];
    protected object $parent_gui;


    public function __construct(object $a_parent_gui, ?ilObject $a_main_obj = null)
    {
        global $DIC;
        $this->ui_services = $DIC->ui();
        $this->http_services = $DIC->http();
        $this->refinery = $DIC->refinery();
        $this->il_user = $DIC->user();
        $this->lng = $DIC->language();
        $this->lng->loadLanguageModule("exp");
        $this->tpl = $DIC->ui()->mainTemplate();
        $this->ctrl = $DIC->ctrl();
        $this->access = $DIC->access();
        $this->error = $DIC['ilErr'];
        $this->toolbar = $DIC->toolbar();
        $this->parent_gui = $a_parent_gui;
        $this->objDefinition = $DIC['objDefinition'];
        $this->tree = $DIC->repositoryTree();
        $this->obj = $a_main_obj ?? $a_parent_gui->getObject();
        $this->gui = $DIC->export()->internal()->gui();
        $this->export_handler = new ilExportHandler();
        $this->export_options = $this->export_handler->consumer()->exportOption()->collection();
        $this->enableStandardXMLExport();
        $this->disablePublicAccessForType("xml");
    }

    protected function initExportOptionsFromPost(): array
    {
        $options = [];
        if ($this->http_services->wrapper()->post()->has('cp_options')) {
            $custom_transformer = $this->refinery->custom()->transformation(
                function ($array) {
                    return $array;
                }
            );
            $options = $this->http_services->wrapper()->post()->retrieve(
                'cp_options',
                $custom_transformer
            );
        }
        return $options;
    }

    protected function builtExportOptionCommand(ilExportHandlerConsumerExportOptionInterface $export_option): string
    {
        return self::CMD_EXPORT_OPTION_PREFIX . $export_option->getExportOptionId();
    }

    public function addExportOption(ilExportHandlerConsumerExportOptionInterface $export_option): void
    {
        $this->export_options = $this->export_options->withExportOption($export_option);
    }

    public function enablePublicAccessForType(string $type)
    {
        $context = $this->export_handler->consumer()->context()->handler($this, $this->obj);
        $allowed_types = $this->export_handler->publicAccess()->typeRestriction()->repository()->handler()->getAllowedTypes(
            new ObjectId($this->obj->getId())
        );
        foreach ($this->export_options as $export_option) {
            $export_option->onPublicAccessTypeRestrictionsChanged($context, $allowed_types);
        }
        $this->export_handler->publicAccess()->typeRestriction()->handler()->addAllowedType(
            new ObjectId($this->obj->getId()),
            $type
        );
    }

    public function disablePublicAccessForType(string $type)
    {
        $context = $this->export_handler->consumer()->context()->handler($this, $this->obj);
        $this->export_handler->publicAccess()->typeRestriction()->handler()->removeAllowedType(
            new ObjectId($this->obj->getId()),
            $type
        );
        $allowed_types = $this->export_handler->publicAccess()->typeRestriction()->repository()->handler()->getAllowedTypes(
            new ObjectId($this->obj->getId())
        );
        foreach ($this->export_options as $export_option) {
            $export_option->onPublicAccessTypeRestrictionsChanged($context, $allowed_types);
        }
    }

    public function enableStandardXMLExport(): void
    {
        $this->addExportOption($this->export_handler->consumer()->exportOption()->basicXml());
    }

    /**
     * @depricated
     */
    public function addFormat(): void
    {

    }

    /**
     * @depricated
     */
    public function addCustomColumn(): void
    {

    }

    /**
     * @depricated
     */
    public function addCustomMultiCommand(): void
    {

    }

    public function getFormats(): array
    {
        return $this->formats;
    }

    public function executeCommand(): void
    {
        # TODO: checkaccess

        // this should work (at least) for repository objects
        if (method_exists($this->obj, 'getRefId') and $this->obj->getRefId()) {
            if (!$this->access->checkAccess('write', '', $this->obj->getRefId())) {
                $this->error->raiseError($this->lng->txt('permission_denied'), $this->error->WARNING);
            }

            // check export activation of container
            $exp_limit = new ilExportLimitation();
            if ($this->objDefinition->isContainer(ilObject::_lookupType($this->obj->getRefId(), true)) &&
                $exp_limit->getLimitationMode() == ilExportLimitation::SET_EXPORT_DISABLED) {
                $this->tpl->setOnScreenMessage('failure', $this->lng->txt("exp_error_disabled"));
                return;
            }
        }

        $cmd = $this->ctrl->getCmd(self::CMD_LIST_EXPORT_FILES);
        if (str_starts_with($cmd, self::CMD_EXPORT_OPTION_PREFIX)) {
            $context = $this->export_handler->consumer()->context()->handler($this, $this->obj);
            foreach ($this->export_options as $export_option) {
                if ($cmd === $this->builtExportOptionCommand($export_option)) {
                    $export_option->onExportOptionSelected($context);
                }
            }
        }
        switch ($cmd) {
            case self::CMD_LIST_EXPORT_FILES:
                $this->listExportFiles();
                break;
            case self::CMD_EXPORT_XML:
                $this->createXMLExportFile();
                break;
            case self::CMD_SAVE_ITEM_SELECTION:
                $this->saveItemSelection();
                break;
        }
    }

    public function listExportFiles(): void
    {
        $table = $this->export_handler->table()->handler()
            ->withExportOptions($this->export_options)
            ->withExportGUI($this)
            ->withExportObject($this->obj);
        $table->handleCommands();
        $context = $this->export_handler->consumer()->context()->handler($this, $this->obj);
        $infos = [];
        foreach ($this->export_options as $export_option) {
            $infos[$export_option->getLabel($context)] = $this->ctrl->getLinkTarget($this, $this->builtExportOptionCommand($export_option));
        }
        if (count($infos) === 1) {
            $this->toolbar->addComponent($this->ui_services->factory()->button()->standard(
                array_keys($infos)[0],
                array_values($infos)[0]
            ));
        }
        if (count($infos) > 1) {
            $links = [];
            foreach ($infos as $label => $link) {
                $links[] = $this->ui_services->factory()->link()->standard($label, $link);
            }
            $this->toolbar->addComponent(
                $this->ui_services->factory()->dropdown()->standard($links)
                ->withLabel($this->lng->txt("exp_export_dropdown"))
            );
        }
        $this->tpl->setContent($table->getHTML());
    }

    protected function createXMLExportFile(): void
    {
        if ($this->parent_gui instanceof  ilContainerGUI) {
            $this->showItemSelection();
            return;
        }
        $this->createXMLExport();
        $this->tpl->setOnScreenMessage(
            ilGlobalTemplateInterface::MESSAGE_TYPE_SUCCESS,
            $this->lng->txt("exp_file_created"),
            true
        );
        $this->ctrl->redirect($this, self::CMD_LIST_EXPORT_FILES);
    }

    /**
     * Show container item selection table
     */
    protected function showItemSelection(): void
    {
        $this->tpl->addJavaScript('assets/js/ilContainer.js');
        $this->tpl->setVariable('BODY_ATTRIBUTES', 'onload="ilDisableChilds(\'cmd\');"');
        $table = new ilExportSelectionTableGUI($this, self::CMD_LIST_EXPORT_FILES, $this->export_handler);
        $table->parseContainer($this->parent_gui->getObject()->getRefId());
        $this->tpl->setContent($table->getHTML());
    }

    protected function saveItemSelection(): void
    {
        // check export limitation
        $cp_options = $this->initExportOptionsFromPost();
        $exp_limit = new ilExportLimitation();
        try {
            $exp_limit->checkLimitation(
                $this->parent_gui->getObject()->getRefId(),
                $cp_options
            );
        } catch (Exception $e) {
            $this->tpl->setOnScreenMessage('failure', $e->getMessage());
            $this->showItemSelection();
            return;
        }
        // create export
        $this->createXMLExport();
        $this->tpl->setOnScreenMessage('success', $this->lng->txt('export_created'), true);
        $this->ctrl->redirect($this, self::CMD_LIST_EXPORT_FILES);
    }

    protected function createXMLExport()
    {
        $eo = ilExportOptions::newInstance(ilExportOptions::allocateExportId());
        $eo->addOption(ilExportOptions::KEY_ROOT, 0, 0, $this->obj->getId());

        $cp_options = $this->initExportOptionsFromPost();

        $items_selected = false;
        foreach ($this->tree->getSubTree($root = $this->tree->getNodeData($this->parent_gui->getObject()->getRefId())) as $node) {
            if ($node['type'] === 'rolf') {
                continue;
            }
            if ($node['ref_id'] == $this->parent_gui->getObject()->getRefId()) {
                $eo->addOption(
                    ilExportOptions::KEY_ITEM_MODE,
                    (int) $node['ref_id'],
                    (int) $node['obj_id'],
                    ilExportOptions::EXPORT_BUILD
                );
                continue;
            }
            // no export available or no access
            if (!$this->objDefinition->allowExport($node['type']) || !$this->access->checkAccess(
                'write',
                '',
                (int) $node['ref_id']
            )) {
                $eo->addOption(
                    ilExportOptions::KEY_ITEM_MODE,
                    (int) $node['ref_id'],
                    (int) $node['obj_id'],
                    ilExportOptions::EXPORT_OMIT
                );
                continue;
            }

            $mode = $cp_options[$node['ref_id']]['type'] ?? ilExportOptions::EXPORT_OMIT;
            $eo->addOption(
                ilExportOptions::KEY_ITEM_MODE,
                (int) $node['ref_id'],
                (int) $node['obj_id'],
                $mode
            );
            if ($mode != ilExportOptions::EXPORT_OMIT) {
                $items_selected = true;
            }
        }
        $ts = time();
        $consumer = $this->export_handler->consumer()->handler();
        if (!$items_selected) {
            $element = $consumer->exportManager()->createExport(
                new ObjectId($this->obj->getId()),
                $this->il_user->getId(),
                $ts,
                ""
            );
        }
        if ($items_selected) {
            $eo->read();
            $ref_ids_export = $eo->getSubitemsForCreation($this->obj->getRefId());
            $ref_ids_all = $eo->getSubitemsForExport();
            $object_ids = $consumer->exportManager()->createObjectIdCollection(
                array_map(function (int $ref_id) { return ilObject::_lookupObjId($ref_id); }, $ref_ids_export),
                array_map(function (int $ref_id) { return ilObject::_lookupObjId($ref_id); }, $ref_ids_all)
            );
            $element = $consumer->exportManager()->createContainerExport(
                $this->il_user->getId(),
                $ts,
                $object_ids->head()->getObjectId(),
                $object_ids->withoutHead()
            );
        }
        $eo->delete();
    }
}
