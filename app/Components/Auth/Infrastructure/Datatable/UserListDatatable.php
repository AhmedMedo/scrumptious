<?php

namespace App\Components\Auth\Infrastructure\Datatable;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Auth\Data\Enums\RoleEnum;
use Yajra\DataTables\DataTables;

class UserListDatatable
{
    /**
     * @throws \Exception
     */
    public function datatable(): \Illuminate\Http\JsonResponse
    {
        $datatable = DataTables::of(
            UserEntity::select([
                'uuid',
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'address',
                'is_active',
                'created_at',
            'updated_at'])
        );
        $datatable->addColumn('uuid', fn(UserEntity $user) => $user->uuid);
        $datatable->addColumn('name', fn(UserEntity $user) => $user->first_name);
        $datatable->addColumn('email', fn(UserEntity $user) => $user->email);
        $datatable->addColumn('phone_number', fn(UserEntity $user) => $user->phone_number);
        $datatable->addColumn('address', fn(UserEntity $user) => $user->address);
        $datatable->addColumn('status', fn(UserEntity $user) => (bool) $user->is_active);
        $datatable->addColumn('created_at', fn(UserEntity $user) => $user->created_at->format('Y-m-d H:i:s'));
        $datatable->addColumn('actions', fn(UserEntity $user) => ['is_suspend' => 0 , 'uuid' => $user->uuid,'status' => 'Active']);

        $datatable->filterColumn('name', function ($query, $keyword) {
            $query->where('first_name', 'like', "%{$keyword}%")->orWhere('last_name', 'like', "%{$keyword}%");
        });
        //email filter
        $datatable->filterColumn('email', function ($query, $keyword) {
            $query->where('email', 'like', "%{$keyword}%");
        });
        $datatable->make(true);
        return $datatable->toJson();
    }
}
