<?php

namespace ILIAS\Export\ExportHandler;

use ilDashboardGUI;
use ILIAS\Export\ExportHandler\ilFactory as ilExportHandler;
use ILIAS\StaticURL\Context;
use ILIAS\StaticURL\Request\Request;
use ILIAS\StaticURL\Response\Factory;
use ILIAS\StaticURL\Response\Response;

class ilStaticUrlHandler
{
    public const NAMESPACE = "export";
    public const DOWNLOAD = 'download';

    protected ilExportHandler $export_handler;

    public function __construct()
    {
        $this->export_handler = new ilExportHandler();
    }

    public function getNamespace(): string
    {
        return self::NAMESPACE;
    }

    public function handle(Request $request, Context $context, Factory $response_factory): Response
    {
        $operation = $request->getAdditionalParameters()[0] ?? null;
        $object_id = $request->getReferenceId()->toObjectId()->toInt() ?? -1;
        $ref_id = $request->getReferenceId()->toInt() ?? -1;

        $access_granted = false;
        if ($context->isUserLoggedIn() and $context->checkPermission("read", $ref_id)) {
            $access_granted = true;
        }
        if ($context->getUserId() === ANONYMOUS_USER_ID and $context->isPublicSectionActive()) {
            $access_granted = true;
        }
        if (!$access_granted or $operation !== self::DOWNLOAD or $object_id === -1) {
            return $response_factory->can($context->ctrl()->getLinkTargetByClass(ilDashboardGUI::class));
        }

        $element = $this->export_handler->publicAccess()->repository()->handler()->getElement($object_id);
        $element->download();
        return $response_factory->cannot();
    }
}
