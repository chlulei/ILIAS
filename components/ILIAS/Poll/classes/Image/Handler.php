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

namespace ILIAS\Poll\Image;

use ILIAS\Data\ObjectId;
use ILIAS\Filesystem\Stream\Streams;
use ILIAS\Poll\Image\I\HandlerInterface as ilPollImageInterface;
use ILIAS\ResourceStorage\Services as ilResourceStorageServices;
use ILIAS\Poll\Image\I\Repository\FactoryInterface as ilPollImageRepositoryFactoryInterface;

class Handler implements ilPollImageInterface
{
    protected ilResourceStorageServices $irss;
    protected ilPollImageRepositoryFactoryInterface $repository;

    public function __construct(
        ilResourceStorageServices $irss,
        ilPollImageRepositoryFactoryInterface $repository
    ) {
        $this->irss = $irss;
        $this->repository = $repository;
    }

    public function uploadImage(
        ObjectId $object_id,
        string $file_path,
        int $user_id
    ): void {
        $rid = $this->irss->manage()->stream(
            Streams::ofResource(fopen($file_path, 'r')),
            $this->repository->stakeholder()->handler()->withUserId($user_id)
        );
        $key = $this->repository->key()->handler()
            ->withObjectId($object_id);
        $values = $this->repository->values()->handler()
            ->withResourceIdSerialized($rid->serialize());
        $this->repository->handler()->store($key, $values);
    }

    public function cloneImage(
        ObjectId $original_object_id,
        ObjectId $clone_object_id,
        int $user_id
    ): void {
        $key_clone = $this->repository->key()->handler()
            ->withObjectId($clone_object_id);
        $key_original = $this->repository->key()->handler()
            ->withObjectId($original_object_id);
        $existing_element = $this->repository->handler()->getElement($key_clone);
        if (!is_null($existing_element)) {
            $existing_element->getIRSS()->delete();
            $this->repository->handler()->deleteElement($existing_element->getKey());
        }
        $element_original = $this->repository->handler()->getElement($key_original);
        $rid_original = $element_original->getIRSS()->getResourceIdentification();
        $rid_clone = $this->irss->manage()->clone($rid_original);
        $values_clone = $this->repository->values()->handler()
            ->withResourceIdSerialized($rid_clone->serialize());
        $this->repository->handler()->store($key_clone, $values_clone);
    }
}
