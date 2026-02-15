<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;

class PostVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const DELETE = 'POST_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // Este voter solo maneja POST_EDIT y POST_DELETE
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        // Solo votamos si el subject es una instancia de Post
        if (!$subject instanceof Post) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // El usuario debe estar autenticado
        if (!$user instanceof User) {
            return false;
        }

        /** @var Post $post */
        $post = $subject;

        return match ($attribute) {
            self::EDIT => $this->canEdit($post, $user),
            self::DELETE => $this->canDelete($post, $user),
            default => throw new \LogicException('Este código no debería ejecutarse'),
        };
    }

    private function canEdit(Post $post, User $user): bool
    {
        // ROLE_ADMIN puede editar cualquier post
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // El autor puede editar su propio post
        return $post->getAuthor() === $user;
    }

    private function canDelete(Post $post, User $user): bool
    {
        // ROLE_ADMIN puede eliminar cualquier post
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // El autor puede eliminar su propio post
        return $post->getAuthor() === $user;
    }
}
