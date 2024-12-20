<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\ProfileRepositoryInterface;
use App\Models\User;
use App\Validators\ProfileValidator;
use Override;
use Exception;

/**
 * Class ProfileRepository.
 *
 * @package namespace App\Repositories;
 */
class ProfileRepository extends BaseRepository implements ProfileRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'profiles';

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function update(User $user, array $data)
    {
        $user->fill($data);

        if($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }

    public function deleteUserAccount(User $user): void
    {
        $user->delete();
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
