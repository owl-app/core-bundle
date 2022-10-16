<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Controller\Action;

use Owl\Bridge\SyliusResource\Controller\AbstractResourceAction;
use Owl\Component\Core\Factory\Document\DocumentFactoryInterface;
use Owl\Component\Core\Factory\Document\Params\PdfDocumentParams;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GenreateLocationsPdfAction extends AbstractResourceAction
{
    /** @var RequestConfigurationFactoryInterface */
    protected $requestConfigurationFactory;

    /** @var ResourcesCollectionProviderInterface */
    protected $resourcesCollectionProvider;

    protected DocumentFactoryInterface $pdfFactory;

    public function __construct(
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        ResourcesCollectionProviderInterface $resourcesFinder,
        DocumentFactoryInterface $pdfFactory
    ) {
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->resourcesCollectionProvider = $resourcesFinder;
        $this->pdfFactory = $pdfFactory;
    }

    public function __invoke(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $paramsPdf = PdfDocumentParams::createFromRequestConfiguration($configuration);

        $this->isGrantedOr403($configuration);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $generatorPdf = $this->pdfFactory->createDocument($paramsPdf);

        return $generatorPdf->generateResponse('PDF-lokalizacje.pdf', ['locations' => $resources]);
    }
}
