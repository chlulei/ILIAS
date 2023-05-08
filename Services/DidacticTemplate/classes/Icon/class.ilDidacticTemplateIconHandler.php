<?php

declare(strict_types=1);

/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

use ILIAS\Filesystem\Filesystem;
use ILIAS\ResourceStorage\Services as ResourceStorageService;

/**
 * Icon handler for didactic template custom icons
 * @author  Stefan Meyer <meyer@leifos.com>
 * @ingroup ServicesDidacticTemplate
 */
class ilDidacticTemplateIconHandler
{
    protected const WEBDIR_PREFIX = 'ilDidacticTemplateIcons';

    protected ilDidacticTemplateSetting $settings;
    protected ilLogger $logger;
    protected Filesystem $webDirectory;

    private ResourceStorageService $storage;

    public function __construct(ilDidacticTemplateSetting $setting)
    {
        global $DIC;
        $this->settings = $setting;
        $this->webDirectory = $DIC->filesystem()->web();
        $this->logger = $DIC->logger()->otpl();
        $this->storage = $DIC->resourceStorage();
    }

    private function isLegacyIcon(): bool
    {
        return preg_match('/^[0-9]+$/', $this->settings->getIconIdentifier()) === 1;
    }

    public function hasIcon(): bool
    {
        return !is_null($this->storage->manage()->find($this->settings->getIconIdentifier()));
    }

    public function updateIconIdentifier(string $iconIdentifier): void
    {
        if ($this->hasIcon() && $iconIdentifier !== '') {
            $this->delete();
        }
        if ($iconIdentifier !== '') {
            $this->settings->setIconIdentifier($iconIdentifier);
        }
    }

    public function writeSvg(string $svg): void
    {
        // TODO: Replace with storage.

        try {
            $this->webDirectory->write(
                self::WEBDIR_PREFIX . '/' . $this->settings->getId() . '.svg',
                trim($svg)
            );
            $this->settings->setIconIdentifier((string) $this->settings->getId());
            $this->settings->update();
        } catch (Exception $e) {
            $this->logger->warning('Error writing svg image from xml: ' . $e->getMessage());
        }
    }

    public function getAbsolutePath(): string
    {
        $isLegacyIcon = $this->isLegacyIcon();
        $resourceId = $this->storage->manage()->find($this->settings->getIconIdentifier());
        if ($isLegacyIcon && $this->webDirectory->has(self::WEBDIR_PREFIX . '/' . $this->settings->getIconIdentifier() . '.svg')) {
            return ilFileUtils::getWebspaceDir() . '/' . self::WEBDIR_PREFIX . '/' . $this->settings->getIconIdentifier() . '.svg';
        } elseif(!$isLegacyIcon && !is_null($resourceId)) {
            $iconSrc = $this->storage->consume()->src($resourceId)->getSrc();
            return $iconSrc;
        }
        return '';
    }

    public function copy(ilDidacticTemplateSetting $original): void
    {
        $isLegacyIcon = $original->getIconHandler()->isLegacyIcon();
        $resourceId = $this->storage->manage()->find($original->getIconIdentifier());
        $absolutePath = $original->getIconHandler()->getAbsolutePath();
        if(!$isLegacyIcon && !is_null($resourceId)) {
            $copyIdentifier = $this->storage->manage()->clone($resourceId)->serialize();
            $this->settings->setIconIdentifier($copyIdentifier);
        } elseif ($isLegacyIcon && $absolutePath) {
            try {
                $this->webDirectory->copy(
                    self::WEBDIR_PREFIX . '/' . $original->getIconIdentifier() . '.svg',
                    self::WEBDIR_PREFIX . '/' . $this->settings->getId() . '.svg'
                );
            } catch (Exception $e) {
                $this->logger->warning('Copying icon failed with message: ' . $e->getMessage());
            }
            $this->settings->setIconIdentifier((string) $this->settings->getId());
        } else {
            $this->settings->setIconIdentifier('');
        }
        $this->settings->update();
    }

    public function delete(): void
    {
        $isLegacyIcon = $this->isLegacyIcon();
        $resourceId = $this->storage->manage()->find($this->settings->getIconIdentifier());
        if(!$isLegacyIcon && !is_null($resourceId)) {
            $this->storage->manage()->remove($resourceId, new ilDidacticTemplateStakeholder());
            $this->settings->setIconIdentifier('');
            $this->settings->update();
        } elseif ($isLegacyIcon && $this->webDirectory->has(self::WEBDIR_PREFIX . '/' . $this->settings->getIconIdentifier() . '.svg')) {
            try {
                $this->webDirectory->delete(self::WEBDIR_PREFIX . '/' . $this->settings->getIconIdentifier() . '.svg');
                $this->settings->setIconIdentifier('');
                $this->settings->update();
            } catch (Exception $e) {
                $this->logger->warning('Deleting icon failed with message: ' . $e->getMessage());
            }
        }
    }

    public function toXml(ilXmlWriter $writer): ilXmlWriter
    {
        $isLegacyIcon = $this->isLegacyIcon();
        $resourceId = $this->storage->manage()->find($this->settings->getIconIdentifier());
        if(!$isLegacyIcon && !is_null($resourceId)) {
            $stream = $this->storage->consume()->stream($resourceId)->getStream();
            try {
                $writer->xmlElement('icon', [], $stream->getContents());
            } catch (Exception $e) {
                $this->logger->warning('Export xml failed with message: ' . $e->getMessage());
            }
        } elseif ($isLegacyIcon && $this->settings->getIconIdentifier()) {
            try {
                if ($this->webDirectory->has(self::WEBDIR_PREFIX . '/' . $this->settings->getIconIdentifier() . '.svg')) {
                    $writer->xmlElement('icon', [], $this->webDirectory->read(
                        self::WEBDIR_PREFIX . '/' . $this->settings->getIconIdentifier() . '.svg'
                    ));
                }
            } catch (Exception $e) {
                $this->logger->warning('Export xml failed with message: ' . $e->getMessage());
            }
        }
        return $writer;
    }
}
