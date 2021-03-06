<?php

namespace AmeliaBooking\Application\Commands\Bookable\Category;

use AmeliaBooking\Application\Commands\CommandHandler;
use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Bookable\Service\Category;
use AmeliaBooking\Domain\Entity\Bookable\Service\Service;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Factory\Bookable\Service\CategoryFactory;
use AmeliaBooking\Application\Common\Exceptions\AccessDeniedException;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\Id;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\Bookable\Service\CategoryRepository;
use AmeliaBooking\Infrastructure\Repository\Bookable\Service\ServiceRepository;

/**
 * Class AddCategoryCommandHandler
 *
 * @package AmeliaBooking\Application\Commands\Bookable\Category
 */
class AddCategoryCommandHandler extends CommandHandler
{
    protected $mandatoryFields = [
        'name'
    ];

    /**
     * @param AddCategoryCommand $command
     *
     * @return CommandResult
     * @throws \Slim\Exception\ContainerValueNotFoundException
     * @throws AccessDeniedException
     * @throws QueryExecutionException
     * @throws InvalidArgumentException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function handle(AddCategoryCommand $command)
    {
        if (!$this->getContainer()->getPermissionsService()->currentUserCanWrite(Entities::SERVICES)) {
            throw new AccessDeniedException('You are not allowed to add category.');
        }

        $result = new CommandResult();

        $this->checkMandatoryFields($command);

        $category = CategoryFactory::create($command->getFields());

        if (!$category instanceof Category) {
            $result->setResult(CommandResult::RESULT_ERROR);
            $result->setMessage('Could not create category.');

            return $result;
        }

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->container->get('domain.bookable.category.repository');
        /** @var ServiceRepository $serviceRepository */
        $serviceRepository = $this->container->get('domain.bookable.service.repository');

        $categoryRepository->beginTransaction();

        if (!($categoryId = $categoryRepository->add($category))) {
            $categoryRepository->rollback();

            $result->setResult(CommandResult::RESULT_ERROR);
            $result->setMessage('Could not create category.');

            return $result;
        }

        $category->setId(new Id($categoryId));

        if ($category->getServiceList() !== null) {
            /** @var Collection $serviceList */
            $serviceList = $category->getServiceList();

            foreach ($serviceList->getItems() as $service) {
                /** @var Service $service */
                $service->setCategoryId(new Id($categoryId));

                if (!$serviceRepository->add($service)) {
                    $categoryRepository->rollback();

                    $result->setResult(CommandResult::RESULT_ERROR);
                    $result->setMessage('Could not create category.');

                    return $result;
                }
            }
        }

        $categoryRepository->commit();

        $result->setResult(CommandResult::RESULT_SUCCESS);
        $result->setMessage('Successfully added new category.');
        $result->setData([
            Entities::CATEGORY => $category->toArray()
        ]);

        return $result;
    }
}
