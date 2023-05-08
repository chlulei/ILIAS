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

use ILIAS\FileUpload\Handler\AbstractCtrlAwareUploadHandler;
use ILIAS\FileUpload\Handler\BasicHandlerResult;
use ILIAS\FileUpload\Handler\FileInfoResult;
use ILIAS\FileUpload\Handler\BasicFileInfoResult;
use ILIAS\FileUpload\Handler\HandlerResult;

use ILIAS\FileUpload\DTO\UploadResult;

use ILIAS\ResourceStorage\Services as ResourceStorageService;

/**
 * @author Christoph Ludolf
 *
 * @ilCtrl_isCalledBy ilDidacticTemplateImageHandlerGUI: ilRepositoryGUI, ilDashboardGUI, ilUIPluginRouterGUI
 */
class ilDidacticTemplateImageHandlerGUI extends AbstractCtrlAwareUploadHandler
{
    private ilDidacticTemplateStakeholder $stakeholder;
    private ResourceStorageService $storage;
    private string $identification;

    public function __construct(string $identification = '')
    {
        global $DIC;
        parent::__construct();

        $this->storage = $DIC->resourceStorage();
        $this->stakeholder = new ilDidacticTemplateStakeholder();
        $this->identification = $identification;
    }

    /**
     * @inheritDoc
     */
    public function getUploadURL(): string
    {
        return $this->ctrl->getLinkTargetByClass([ilUIPluginRouterGUI::class, self::class], self::CMD_UPLOAD);
    }

    /**
     * @inheritDoc
     */
    public function getExistingFileInfoURL(): string
    {
        return $this->ctrl->getLinkTargetByClass([ilUIPluginRouterGUI::class, self::class], self::CMD_INFO);
    }

    /**
     * @inheritDoc
     */
    public function getFileRemovalURL(): string
    {
        return $this->ctrl->getLinkTargetByClass([ilUIPluginRouterGUI::class, self::class], self::CMD_REMOVE, null, false);
    }

    /**
     * @inheritDoc
     */
    public function getFileIdentifierParameterName(): string
    {
        return 'icon_file';
    }

    /**
     * @inheritDoc
     */
    protected function getUploadResult(): HandlerResult
    {
        $status = HandlerResult::STATUS_FAILED;
        $newIdentification = 'unknown';
        $message = 'file upload failed';

        $this->upload->process();
        $uploadResults = $this->upload->getResults();
        $result = end($uploadResults);

        if($result instanceof UploadResult && $result->isOK() && $result->getSize()) {
            $resourceId = $this->storage->manage()->find($this->identification);
            if($this->identification === '') {
                $newIdentification = $this->storage->manage()->upload($result, $this->stakeholder)->serialize();
                $status = HandlerResult::STATUS_OK;
                $message = 'Upload ok';
            } elseif(!is_null($resourceId)) {
                $this->storage->manage()->replaceWithUpload($resourceId, $result, $this->stakeholder);
                $newIdentification = $this->identification;
                $status = HandlerResult::STATUS_OK;
                $message = 'Upload ok';
            }
        }
        return new BasicHandlerResult(
            $this->getFileIdentifierParameterName(),
            $status,
            $newIdentification,
            $message
        );
    }

    protected function getRemoveResult(string $identifier): HandlerResult
    {
        if (null !== ($id = $this->storage->manage()->find($identifier))) {
            $this->storage->manage()->remove($id, $this->stakeholder);
            $status = HandlerResult::STATUS_OK;
            $message = "file removal OK";
        } else {
            $status = HandlerResult::STATUS_OK;
            $message = "file with identifier '$identifier' doesn't exist, nothing to do.";
        }

        return new BasicHandlerResult(
            $this->getFileIdentifierParameterName(),
            $status,
            $identifier,
            $message
        );
    }

    public function getInfoResult(string $identifier): ?FileInfoResult
    {
        if (null !== ($id = $this->storage->manage()->find($identifier))) {
            $revision = $this->storage->manage()->getCurrentRevision($id)->getInformation();
            $title = $revision->getTitle();
            $size = $revision->getSize();
            $mime = $revision->getMimeType();
        } else {
            $title = $mime = 'unknown';
            $size = 0;
        }

        return new BasicFileInfoResult(
            $this->getFileIdentifierParameterName(),
            $identifier,
            $title,
            $size,
            $mime
        );
    }

    public function getInfoForExistingFiles(array $file_ids): array
    {
        $info_results = [];
        foreach ($file_ids as $identifier) {
            $info_results[] = $this->getInfoResult($identifier);
        }

        return $info_results;
    }
}
