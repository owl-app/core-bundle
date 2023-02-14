<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Controller\Action;

use Owl\Bridge\SyliusResource\Controller\AbstractResourceAction;
use Owl\Bridge\SyliusResource\Exception\InvalidResponseException;
use Owl\Component\Core\Factory\Document\DocumentFactoryInterface;
use Owl\Component\Core\Factory\Document\Params\ExcelDocumentParams;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GenreateLocationsExcelAction extends AbstractResourceAction
{
    /** @var RequestConfigurationFactoryInterface */
    protected $requestConfigurationFactory;

    /** @var ResourcesCollectionProviderInterface */
    protected $resourcesCollectionProvider;

    protected FormFactoryInterface $formFactory;

    protected DocumentFactoryInterface $excelFactory;

    public function __construct(
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        ResourcesCollectionProviderInterface $resourcesFinder,
        FormFactoryInterface $formFactory,
        DocumentFactoryInterface $excelFactory
    ) {
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->resourcesCollectionProvider = $resourcesFinder;
        $this->formFactory = $formFactory;
        $this->excelFactory = $excelFactory;
    }

    public function __invoke(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration);

        $formType = (string) $configuration->getFormType();
        $formOptions = $configuration->getFormOptions();

        $form = $this->formFactory->create($formType, null, $formOptions);
        $form->handleRequest($request);

        if ($configuration->isAjaxRequest() && $form->isSubmitted() && !$form->isValid()) {
            try {
                return $this->eventAjaxValidation($configuration, $form);
            } catch (InvalidResponseException $e) {
                throw $e;
            }
        }

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

            $documentParams = ExcelDocumentParams::createFromRequestConfiguration($configuration);
            $documentParams->setWriter($formData['type'] ?? 'xlsx');

            $generatorExcel = $this->excelFactory->createDocument($documentParams);

            return $generatorExcel->generateResponse('EXCEL-lokalizacje', ['locations' => $resources]);
        }

        return $this->render($configuration->getTemplate(''), [
            'configuration' => $configuration,
            'metadata' => $this->metadata,
            'form' => $form->createView(),
        ]);
    }
}
