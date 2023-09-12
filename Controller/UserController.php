<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Controller;

use Exception;
use Owl\Bridge\SyliusResource\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Owl\Bundle\RbacBundle\Factory\PermissionFormFactoryInterface;
use Owl\Bundle\RbacManagerBundle\Factory\ItemFactoryInterface;
use Owl\Bundle\RbacManagerBundle\Manager\ManagerInterface;
use Owl\Bundle\UserBundle\Controller\UserController as BaseUserController;

class UserController extends BaseUserController
{
    public function availablesAction(Request $request, PermissionFormFactoryInterface $permissionFormFactory, ManagerInterface $rbacManager): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, 'availables');

        $resource = $this->findOr404($configuration);
        [$permissionsDirect, $permissionsInherited] = $rbacManager->getPermissionsByUser($resource->getId(), true);
        $userRoles = $rbacManager->getRolesByUser($resource->getId());

        $forms = $permissionFormFactory->createByExists(
            $configuration,
            array_keys(array_merge($permissionsDirect, $permissionsInherited, $userRoles)),
            array_diff(array_keys($permissionsInherited), array_keys($permissionsDirect)),
            true
        );

        return $this->render($configuration->getTemplate('index.html'), [
            'configuration' => $configuration,
            'metadata' => $this->metadata,
            'user' => $resource,
            'forms' => $forms
        ]);
    }

    public function assignAction(Request $request, ManagerInterface $rbacManager, ItemFactoryInterface $rbacItemFactory): Response
    {
        return $this->changePermission('assign', $request, $rbacManager, $rbacItemFactory);
    }

    public function revokeAction(Request $request, ManagerInterface $rbacManager, ItemFactoryInterface $rbacItemFactory): Response
    {
        return $this->changePermission('revoke', $request, $rbacManager, $rbacItemFactory);
    }

    /**
     * @return Response|null
     */
    private function changePermission(string $action, Request $request, ManagerInterface $rbacManager, ItemFactoryInterface $rbacItemFactory)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $formOptions = array_merge(
            $configuration->getFormOptions(),
            [
                'csrf_field_name' => '_csrf_token',
                'csrf_token_id' => $request->request->get('name')
            ]
        );
        $method = $action === 'revoke' ? 'DELETE' : 'POST';

        $this->isGrantedOr403($configuration, $action);
        $user = $this->findOr404($configuration);

        $form = $this->container->get('form.factory')->createNamed('', $configuration->getFormType(), null, $formOptions);
        $form->handleRequest($request);

        if ($request->isMethod($method) && $form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $assignItem = $rbacItemFactory->create($formData['type'], $formData['name']);

            try {
                $rbacManager->{$action}($assignItem, $user->getId());

                if (!$configuration->isHtmlRequest()) {
                    [$permissionsDirect, $permissionsInherited] = $rbacManager->getPermissionsByUser($user->getId(), true);
                    $userRoles = $rbacManager->getRolesByUser($user->getId());

                    $responseData = [
                        'message' => $this->get('translator')->trans('owl.rbac.permission.add_success', [], 'flashes'),
                        'permissions' => [
                            'direct' => array_keys(array_merge($permissionsDirect, $userRoles)),
                            'inherited' => array_keys($permissionsInherited)
                        ]
                    ];

                    return $this->createRestView($configuration, $responseData, Response::HTTP_OK);
                }
            } catch(Exception $e) {
                $responseData = [
                    'message' => $e->getMessage()
                ];
                return $this->createRestView($configuration, $responseData, Response::HTTP_BAD_REQUEST);
            }
        } else {
            $responseData = [
                'message' => [
                    'status' => 'error',
                    'errors' => $this->getErrorMessages($form)
                ]
            ];
            return $this->createRestView($configuration, $responseData, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
