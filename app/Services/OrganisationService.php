<?php

declare(strict_types=1);

namespace App\Services;

use App\Organisation;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Class OrganisationService
 * @package App\Services
 */
class OrganisationService
{
    /**
     * Create Organisation Service / Request validate
     * @param array $attributes
     * @return Organisation|Application|ResponseFactory|Response
     */
    public function createOrganisation(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = Auth::guard('api')->user();
        $attributes['owner_user_id'] = $user['id'];
        $attributes['subscribed'] = 1;
        $attributes['trial_end'] = (new Carbon)->addDays(30)->toDateTimeString();
        $organisation = Organisation::make($attributes);
        $organisation->save();
        return $organisation;
    }
}
