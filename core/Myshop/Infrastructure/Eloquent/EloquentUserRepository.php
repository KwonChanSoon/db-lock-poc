<?php

namespace Myshop\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Myshop\Common\Model\QueryOperator;
use Myshop\Domain\Model\User;
use Myshop\Domain\Repository\UserRepository;

class EloquentUserRepository implements UserRepository
{
    /**
     * {@inheritdoc}
     */
    public function all(): Collection
    {
        return User::all();
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findByName(string $name, QueryOperator $operator = null): User
    {
        $operator = $operator ?: QueryOperator::EQUAL();

        return User::where('name', $operator, $name)->firstOrFail();
    }

    /**
     * {@inheritdoc}
     * @throws ModelNotFoundException
     */
    public function findByEmail(string $email, QueryOperator $operator = null): User
    {
        $operator = $operator ?: QueryOperator::EQUAL();

        return User::where('email', $operator, $email)->firstOrFail();
    }

    public function save(User $user)
    {
        $user->push();
    }

    public function delete(User $user)
    {
        $user->delete();
    }
}