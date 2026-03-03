<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\NotificationRequestType;
use App\Repository\UserRepository;
use App\Request\NotificationRequestModel;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notifications')]
class NotificationController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly NotificationService $notificationService
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $requestModel = new NotificationRequestModel();
        $form = $this->createForm(NotificationRequestType::class, $requestModel);

        $form->submit($request->query->all());

        if (!$form->isValid()) {
            return $this->json(['errors' => (string) $form->getErrors(true)], 400);
        }

        $user = $this->userRepository->find($requestModel->getUserId());

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $notifications = $this->notificationService->getNotificationsForUser($user);

        return $this->json($notifications);
    }
}
