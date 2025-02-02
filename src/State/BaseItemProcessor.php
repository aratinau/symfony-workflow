<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Workflow\WorkflowInterface;

##[AsDecorator('api_platform.doctrine.orm.state.persist_processor')]
class BaseItemProcessor // implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $innerProcessor,
        ##[Target('public_status')]
        private WorkflowInterface $workflow,
        private LoggerInterface $logger
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->workflow->apply($data, 'to_read');

        $this->logger->info(sprintf(
            'Transition from SENT to READ executed for item with ID %d',
            $data->getId()
        ));

//        if ($data instanceof User && $data->getPlainPassword()) {
//            $data->setPassword($this->userPasswordHasher->hashPassword($data, $data->getPlainPassword()));
//        }

        $this->innerProcessor->process($data, $operation, $uriVariables, $context);

        return $data;
    }
}
