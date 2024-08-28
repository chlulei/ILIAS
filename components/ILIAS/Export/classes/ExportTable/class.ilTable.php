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

use ilCalendarSettings;
use ilExportGUI;
use ILIAS\Export\ExportHandler\ilFactory as ilExportHandler;
use ILIAS\Export\ExportHandler\I\Consumer\File\ilCollectionInterface as ilExportHandlerConsumerFileCollectionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\Identification\ilCollectionInterface as ilExportHandlerConsumerFileIdentificationCollectionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilHandlerInterface as ilExportHandlerConsumerExportOptionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilCollectionInterface as ilExportHandlerConsumerExportOptionCollectionInterface;
use ILIAS\Data\Factory as ilDataFactory;
use ILIAS\Refinery\Factory as ilRefineryFactory;
use ILIAS\UI\URLBuilder;
use ILIAS\DI\UIServices as ilUIServices;
use ilObject;
use JetBrains\PhpStorm\NoReturn;
use ILIAS\Data\DateFormat\Factory as ilDateFormatFactory;
use ILIAS\UI\Component\Table\Column\Column as ilTableColumnIntrerface;
use ILIAS\HTTP\Services as ilHTTPServices;
use ILIAS\UI\URLBuilderToken as ilURLBuilderToken;
use ILIAS\UI\Component\Table\Data as ilDataTable;
use ILIAS\UI\Component\Button\Button as ilButton;
use ilLanguage;
use ilObjUser;

class ilTable
{
    protected const TABLE_ID = "export";
    protected const ROW_ID = "row_ids";
    protected const TABLE_ACTION_ID = "table_action";
    protected const ACTION_DELETE = "delete";
    protected const ACTION_DOWNLOAD = "download";
    protected const ACTION_PUBLIC_ACCESS = "enable_pa";
    protected const ACTION_CONFIRM_DELETE = "delete_selected";

    protected ilUIServices $ui_services;
    protected ilHTTPServices $http_services;
    protected ilRefineryFactory $refinery;
    protected ilObjUser $user;
    protected ilLanguage $lng;
    protected ilExportHandler $export_handler;
    protected ilDataFactory $data_factory;
    protected ilDataRetreival $data_retreival;
    protected URLBuilder $url_builder;
    protected ilURLBuilderToken $action_parameter_token;
    protected ilURLBuilderToken $row_id_token;
    protected ilDataTable $table;
    protected ilExportHandlerConsumerExportOptionCollectionInterface $export_options;
    protected ilObject $export_object;
    protected ilExportGUI $export_gui;

    public function __construct(
        ilUIServices $ui_services,
        ilHTTPServices $http_services,
        ilRefineryFactory $refinery,
        ilObjUser $user,
        ilLanguage $lng,
        ilExportHandler $export_handler,
        ilExportGUI $export_gui,
        ilObject $export_object,
        ilExportHandlerConsumerExportOptionCollectionInterface $export_options
    ) {
        $this->http_services = $http_services;
        $this->ui_services = $ui_services;
        $this->refinery = $refinery;
        $this->lng = $lng;
        $this->lng->loadLanguageModule("exp");
        $this->user = $user;
        $this->export_object = $export_object;
        $this->export_gui = $export_gui;
        $this->export_handler = $export_handler;
        $this->data_factory = new ilDataFactory();
        $this->data_retreival = new ilDataRetreival(
            $this->ui_services,
            $this->export_handler,
            $this->export_gui,
            $this->export_object,
            $export_options
        );
        $this->export_options = $export_options;
        $this->table = $this->ui_services->factory()->table()->data(
            $this->lng->txt("export_table_name"),
            $this->getColumns(),
            $this->data_retreival
        )
            ->withId(self::TABLE_ID)
            ->withActions($this->getActions())
            ->withRequest($this->http_services->request());
    }

    protected function getColumns(): array
    {
        if ((int) $this->user->getTimeFormat() === ilCalendarSettings::TIME_FORMAT_12) {
            $format = $this->data_factory->dateFormat()->withTime12($this->user->getDateFormat());
        } else {
            $format = $this->data_factory->dateFormat()->withTime24($this->user->getDateFormat());
        }
        return [
            'type' => $this->ui_services->factory()->table()->column()->text($this->lng->txt('type'))
                ->withHighlight(true),
            'file' => $this->ui_services->factory()->table()->column()->text($this->lng->txt('file'))
                ->withHighlight(true),
            'size' => $this->ui_services->factory()->table()->column()->number($this->lng->txt('size'))
                ->withHighlight(true),
            'timestamp' => $this->ui_services->factory()->table()->column()->date($this->lng->txt('date'), $format),
            'public_access' => $this->ui_services->factory()->table()->column()->statusIcon($this->lng->txt('public_access'))
        ];
    }

    protected function getActions(): array
    {
        $this->url_builder = new URLBuilder($this->data_factory->uri($this->http_services->request()->getUri()->__toString()));
        list($this->url_builder, $this->action_parameter_token, $this->row_id_token) =
            $this->url_builder->acquireParameters(
                ['datatable', self::TABLE_ID],
                self::TABLE_ACTION_ID,
                self::ROW_ID
            );
        return [
            self::ACTION_PUBLIC_ACCESS => $this->ui_services->factory()->table()->action()->single(
                $this->lng->txt('toggle_public_access'),
                $this->url_builder->withParameter($this->action_parameter_token, self::ACTION_PUBLIC_ACCESS),
                $this->row_id_token
            ),
            self::ACTION_DOWNLOAD => $this->ui_services->factory()->table()->action()->single(
                $this->lng->txt('download'),
                $this->url_builder->withParameter($this->action_parameter_token, self::ACTION_DOWNLOAD),
                $this->row_id_token
            ),
            self::ACTION_DELETE => $this->ui_services->factory()->table()->action()->standard(
                $this->lng->txt('delete'),
                $this->url_builder->withParameter($this->action_parameter_token, self::ACTION_DELETE),
                $this->row_id_token
            )->withAsync()
        ];
    }

    public function handleCommands(): void
    {
        if (!$this->http_services->wrapper()->query()->has($this->action_parameter_token->getName())) {
            return;
        }
        $action = $this->http_services->wrapper()->query()->retrieve(
            $this->action_parameter_token->getName(),
            $this->refinery->to()->string()
        );
        $ids = $this->http_services->wrapper()->query()->retrieve(
            $this->row_id_token->getName(),
            $this->refinery->custom()->transformation(fn($v) => $v)
        );
        $ids = is_array($ids) ? $ids : [$ids];
        $ids_sorted = [];
        foreach ($ids as $id) {
            $file_identifier = $this->export_handler->consumer()->file()->identification()->handler()->withCompositId($id);
            $export_option = $this->export_options->getMatchingExportOption($file_identifier);
            if (!isset($ids_sorted[$file_identifier->getExportOptionId()])) {
                $ids_sorted[$file_identifier->getExportOptionId()] = $this->export_handler->consumer()->file()->identification()->collection();
            }
            $ids_sorted[$file_identifier->getExportOptionId()] = $ids_sorted[$file_identifier->getExportOptionId()]->withElement($file_identifier);
        }
        switch ($action) {
            case self::ACTION_PUBLIC_ACCESS:
                $this->markAsPublicAccess($ids_sorted);
                break;
            case self::ACTION_DOWNLOAD:
                $this->downloadItems($ids_sorted);
                break;
            case self::ACTION_DELETE:
                $this->showDeleteModal($ids_sorted);
                break;
            case self::ACTION_CONFIRM_DELETE:
                $this->deleteItems($ids_sorted);
                break;
        }
    }

    /**
     * @param array<string, ilExportHandlerConsumerFileIdentificationCollectionInterface> $ids_sorted
     */
    #[NoReturn] protected function showDeleteModal(array $ids_sorted): void
    {
        $items = [];
        $ids = [];
        foreach ($ids_sorted as $export_option_id => $file_ids) {
            $export_option = $this->export_options->getMatchingExportOption($file_ids->current());
            $file_infos = $export_option->getFileSelection(
                $this->export_handler->consumer()->context()->handler($this->export_gui, $this->export_object),
                $file_ids
            );
            for ($i = 0; $i < $file_infos->count(); $i++) {
                $file_id = $file_ids->elementAt($i);
                $file_info = $file_infos->elementAt($i);
                $ids[] = $file_id->compositId();
                $items[] = $this->ui_services->factory()->modal()->interruptiveItem()->keyValue(
                    $file_id->compositId(),
                    $file_id->getFileId(),
                    $file_info->getFileName()
                );
            }
        }
        echo($this->ui_services->renderer()->renderAsync([
            $this->ui_services->factory()->modal()->interruptive(
                'Deletion',
                'You are about to delete items!',
                (string) $this->url_builder
                    ->withParameter(
                        $this->action_parameter_token,
                        self::ACTION_CONFIRM_DELETE
                    )->withParameter(
                        $this->row_id_token,
                        $ids
                    )->buildURI()
            )->withAffectedItems($items)
        ]));
        exit();
    }

    /**
     * @param array<string, ilExportHandlerConsumerFileIdentificationCollectionInterface> $ids_sorted
     */
    protected function deleteItems(array $ids_sorted): void
    {
        foreach ($ids_sorted as $export_option_id => $file_ids) {
            $export_option = $this->export_options->getMatchingExportOption($file_ids->current());
            $export_option->onDeleteFiles(
                $this->export_handler->consumer()->context()->handler($this->export_gui, $this->export_object),
                $file_ids
            );
        }
    }

    /**
     * @param array<string, ilExportHandlerConsumerFileIdentificationCollectionInterface> $ids_sorted
     */
    public function markAsPublicAccess(array $ids_sorted): void
    {
        $pat_restriction = $this->export_handler->publicAccess()->typeRestriction()->handler();
        $pa_repository = $this->export_handler->publicAccess()->repository()->handler();
        $pa_repository_element_factory = $this->export_handler->publicAccess()->repository()->element();
        $context = $this->export_handler->consumer()->context()->handler($this->export_gui, $this->export_object);
        foreach ($ids_sorted as $export_option_id => $file_ids) {
            $export_option = $this->export_options->getMatchingExportOption($file_ids->current());
            if (!$pat_restriction->isTypeAllowed($this->export_object->getId(), $export_option->getExportType())) {
                continue;
            }
            foreach ($export_option->getFileSelection($context, $file_ids) as $file_info) {
                if (!$file_info->getPublicAccessPossible()) {
                    continue;
                }
                $element = $pa_repository_element_factory->handler()
                    ->withIdentification($file_info->getFileIdentifier())
                    ->withObjectId($this->export_object->getId());
                if (
                    $pa_repository->hasElement($element)
                ) {
                    $pa_repository->deleteElement($element);
                    continue;
                }
                $pa_repository->storeElement($element);
            }
        }
        $context->ilCtrl()->redirect($this->export_gui, ilExportGUI::CMD_LIST_EXPORT_FILES);
    }

    /**
     * @param array<string, ilExportHandlerConsumerFileIdentificationCollectionInterface> $ids_sorted
     */
    protected function downloadItems(array $ids_sorted): void
    {
        foreach ($ids_sorted as $export_option_id => $file_ids) {
            $export_option = $this->export_options->getMatchingExportOption($file_ids->current());
            $export_option->onDownloadFiles(
                $this->export_handler->consumer()->context()->handler($this->export_gui, $this->export_object),
                $file_ids
            );
        }
    }

    public function getHTML(): string
    {
        return $this->ui_services->renderer()->render([$this->table]);
    }
}
