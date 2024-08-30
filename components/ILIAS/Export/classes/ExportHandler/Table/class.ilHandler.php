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

namespace ILIAS\Export\ExportHandler\Table;

use ilCalendarSettings;
use ilExportGUI;
use ILIAS\Data\Factory as ilDataFactory;
use ILIAS\Data\ReferenceId;
use ILIAS\DI\UIServices as ilUIServices;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilCollectionInterface as ilExportHandlerConsumerExportOptionCollectionInterface;
use ILIAS\Export\ExportHandler\I\Table\ilHandlerInterface as ilExportHandlerTableInterface;
use ILIAS\Export\ExportHandler\I\Table\RowId\ilCollectionInterface as ilExportHandlerTableRowCollectionInterface;
use ILIAS\Export\ExportHandler\ilFactory as ilExportHandler;
use ILIAS\HTTP\Services as ilHTTPServices;
use ILIAS\Refinery\Factory as ilRefineryFactory;
use ILIAS\UI\Component\Table\Data as ilDataTable;
use ILIAS\UI\URLBuilder;
use ILIAS\UI\URLBuilderToken as ilURLBuilderToken;
use ilLanguage;
use ilObject;
use ilObjUser;
use JetBrains\PhpStorm\NoReturn;

class ilHandler implements ilExportHandlerTableInterface
{
    protected const TABLE_COL_LNG_TYPE = 'exp_type';
    protected const TABLE_COL_LNG_FILE = 'exp_file';
    protected const TABLE_COL_LNG_SIZE = 'exp_size';
    protected const TABLE_COL_LNG_TIMESTAMP = 'exp_timestamp';
    protected const TABLE_COL_LNG_PUBLIC_ACCESS = 'exp_public_access';
    protected const TABLE_COL_LNG_PUBLIC_ACCESS_POSSIBLE = 'exp_public_access_possible';
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
    protected URLBuilder $url_builder;
    protected ilURLBuilderToken $action_parameter_token;
    protected ilURLBuilderToken $row_id_token;
    protected ilExportHandlerConsumerExportOptionCollectionInterface $export_options;
    protected ilObject $export_object;
    protected ilExportGUI $export_gui;
    protected ilDataTable $table;

    public function __construct(
        ilUIServices $ui_services,
        ilHTTPServices $http_services,
        ilRefineryFactory $refinery,
        ilObjUser $user,
        ilLanguage $lng,
        ilExportHandler $export_handler
    ) {
        $this->http_services = $http_services;
        $this->ui_services = $ui_services;
        $this->refinery = $refinery;
        $this->lng = $lng;
        $this->lng->loadLanguageModule("exp");
        $this->user = $user;
        $this->export_handler = $export_handler;
        $this->data_factory = new ilDataFactory();
    }

    protected function getColumns(): array
    {
        if ((int) $this->user->getTimeFormat() === ilCalendarSettings::TIME_FORMAT_12) {
            $format = $this->data_factory->dateFormat()->withTime12($this->user->getDateFormat());
        } else {
            $format = $this->data_factory->dateFormat()->withTime24($this->user->getDateFormat());
        }
        return [
            self::TABLE_COL_TYPE => $this->ui_services->factory()->table()->column()->text(
                $this->lng->txt(self::TABLE_COL_LNG_TYPE)
            )->withHighlight(true),
            self::TABLE_COL_FILE => $this->ui_services->factory()->table()->column()->text(
                $this->lng->txt(self::TABLE_COL_LNG_FILE)
            )->withHighlight(true),
            self::TABLE_COL_SIZE => $this->ui_services->factory()->table()->column()->number(
                $this->lng->txt(self::TABLE_COL_LNG_SIZE)
            )
                ->withHighlight(true)
                ->withDecimals(4),
            self::TABLE_COL_TIMESTAMP => $this->ui_services->factory()->table()->column()->date(
                $this->lng->txt(self::TABLE_COL_LNG_TIMESTAMP),
                $format
            ),
            self::TABLE_COL_PUBLIC_ACCESS => $this->ui_services->factory()->table()->column()->statusIcon(
                $this->lng->txt(self::TABLE_COL_LNG_PUBLIC_ACCESS),
            ),
            self::TABLE_COL_PUBLIC_ACCESS_POSSIBLE => $this->ui_services->factory()->table()->column()->statusIcon(
                $this->lng->txt(self::TABLE_COL_LNG_PUBLIC_ACCESS_POSSIBLE),
            )
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

    /**
     * @param array<string, ilExportHandlerTableRowCollectionInterface> $ids_sorted
     */
    #[NoReturn] protected function showDeleteModal(array $ids_sorted): void
    {
        $items = [];
        $ids = [];
        $context = $this->export_handler->consumer()->context()->handler($this->export_gui, $this->export_object);
        foreach ($ids_sorted as $export_option_id => $table_row_ids) {
            $export_option = $this->export_options->getById($export_option_id);
            foreach ($export_option->getFileSelection($context, $table_row_ids) as $file_info) {
                $table_row_id = $this->export_handler->table()->rowId()->handler()
                    ->withExportOptionId($export_option_id)
                    ->withFileIdentifier($file_info->getFileIdentifier());
                $ids[] = $table_row_id->getCompositId();
                $items[] = $this->ui_services->factory()->modal()->interruptiveItem()->keyValue(
                    $table_row_id->getCompositId(),
                    $table_row_id->getFileIdentifier(),
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
     * @param array<string, ilExportHandlerTableRowCollectionInterface> $ids_sorted
     */
    protected function deleteItems(array $ids_sorted): void
    {
        foreach ($ids_sorted as $export_option_id => $table_row_ids) {
            $export_option = $this->export_options->getById($export_option_id);
            $export_option->onDeleteFiles(
                $this->export_handler->consumer()->context()->handler($this->export_gui, $this->export_object),
                $table_row_ids
            );
        }
    }

    /**
     * @param array<string, ilExportHandlerTableRowCollectionInterface> $ids_sorted
     */
    protected function markAsPublicAccess(array $ids_sorted): void
    {
        $pat_restriction = $this->export_handler->publicAccess()->typeRestriction()->handler();
        $pa_repository = $this->export_handler->publicAccess()->repository()->handler();
        $pa_repository_element_factory = $this->export_handler->publicAccess()->repository()->element();
        $context = $this->export_handler->consumer()->context()->handler($this->export_gui, $this->export_object);
        $ref_id = new ReferenceId($context->exportObject()->getRefId());
        foreach ($ids_sorted as $export_option_id => $table_row_ids) {
            $export_option = $this->export_options->getById($export_option_id);
            $type_allowed = $pat_restriction->isTypeAllowed($ref_id, $export_option->getExportType());
            foreach ($export_option->getFileSelection($context, $table_row_ids) as $file_info) {
                $element = $pa_repository_element_factory->handler()
                    ->withIdentification($file_info->getFileIdentifier())
                    ->withReferenceId($ref_id);
                if ($pa_repository->hasElement($element)) {
                    $pa_repository->deleteElement($element);
                    continue;
                }
                if (!$file_info->getPublicAccessPossible() or !$type_allowed) {
                    continue;
                }
                $pa_repository->storeElement($element);
            }
        }
        $context->ilCtrl()->redirect($this->export_gui, ilExportGUI::CMD_LIST_EXPORT_FILES);
    }

    /**
     * @param array<string, ilExportHandlerTableRowCollectionInterface> $ids_sorted
     */
    protected function downloadItems(array $ids_sorted): void
    {
        foreach ($ids_sorted as $export_option_id => $table_row_ids) {
            $export_option = $this->export_options->getById($export_option_id);
            $export_option->onDownloadFiles(
                $this->export_handler->consumer()->context()->handler($this->export_gui, $this->export_object),
                $table_row_ids
            );
        }
    }

    protected function initTable(): void
    {
        if (isset($this->table)) {
            return;
        }
        $this->table = $this->ui_services->factory()->table()->data(
            $this->lng->txt("export_table_name"),
            $this->getColumns(),
            $this->export_handler->table()->dataRetrieval()
                ->withExportOptions($this->export_options)
                ->withExportObject($this->export_object)
                ->withExportGUI($this->export_gui)
        )
            ->withId(self::TABLE_ID)
            ->withActions($this->getActions())
            ->withRequest($this->http_services->request());
    }

    public function handleCommands(): void
    {
        $this->initTable();
        if (!$this->http_services->wrapper()->query()->has($this->action_parameter_token->getName())) {
            return;
        }
        $action = $this->http_services->wrapper()->query()->retrieve(
            $this->action_parameter_token->getName(),
            $this->refinery->to()->string()
        );
        $composit_ids = $this->http_services->wrapper()->query()->retrieve(
            $this->row_id_token->getName(),
            $this->refinery->custom()->transformation(fn($v) => $v)
        );
        $composit_ids = is_array($composit_ids) ? $composit_ids : [$composit_ids];
        $ids_sorted = [];
        foreach ($composit_ids as $composit_id) {
            $table_row_id = $this->export_handler->table()->rowId()->handler()
                ->withCompositId($composit_id);
            $export_option = $this->export_options->getById($table_row_id->getExportOptionId());
            if (!isset($ids_sorted[$table_row_id->getExportOptionId()])) {
                $ids_sorted[$table_row_id->getExportOptionId()] = $this->export_handler->table()->rowId()->collection();
            }
            $ids_sorted[$table_row_id->getExportOptionId()] = $ids_sorted[$table_row_id->getExportOptionId()]
                ->withRowId($table_row_id);
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

    public function getHTML(): string
    {
        $this->initTable();
        return $this->ui_services->renderer()->render([$this->table]);
    }

    public function withExportOptions(
        ilExportHandlerConsumerExportOptionCollectionInterface $export_options
    ): ilExportHandlerTableInterface {
        $clone = clone $this;
        $clone->export_options = $export_options;
        return $clone;
    }

    public function withExportGUI(ilExportGUI $export_gui): ilExportHandlerTableInterface
    {
        $clone = clone $this;
        $clone->export_gui = $export_gui;
        return $clone;
    }

    public function withExportObject(ilObject $export_object): ilExportHandlerTableInterface
    {
        $clone = clone $this;
        $clone->export_object = $export_object;
        return $clone;
    }
}
