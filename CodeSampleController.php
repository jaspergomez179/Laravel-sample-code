<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LymPrtCatryRequest;
use App\Http\Requests\LymPrtCatryUpdateRequest;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\LymPrtCatryCollection;
use App\Http\Resources\LymPrtCatryResource;
use App\Models\LymPrtCatry;
use App\Models\LytLoginUsr;
use Illuminate\Http\Request;

/*
   |--------------------------------------------------------------------------
   | lymprtcatry Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the lymprtcatry of
     index,
     show,
     store,
     update,
     destroy,
   |
   */


class LymPrtCatryAPIController extends Controller
{
    /**
     * list lymprtcatry
     * @param Request $request
     * @return LymPrtCatryCollection
     */
    public function index(Request $request)
    {
        if ($request->get('is_light', false)) {
            $lymprtcatry = new LymPrtCatry();
            $query = LytLoginUsr::commonFunctionMethod(LymPrtCatry::select($lymprtcatry->light), $request, true);
            return new LymPrtCatryCollection(LymPrtCatryResource::collection($query), LymPrtCatryResource::class);
        } else {
            $query = LytLoginUsr::commonFunctionMethod(LymPrtCatry::with(['inflCodeData']), $request, true);
        }
        return new LymPrtCatryCollection(LymPrtCatryResource::collection($query), LymPrtCatryResource::class);
    }

    /**
     * LymPrtCatry Detail
     * @param LymPrtCatry $lymPrtcatry
     * @return LymPrtCatryResource
     */
    public function show(LymPrtCatry $lymprtcatry)
    {
        return new LymPrtCatryResource($lymprtcatry->load([]));
    }

    /**
     * Add LymPrtCatry
     * @param LymPrtCatryRequest $request
     * @return LymPrtCatryResource
     */
    public function store(LymPrtCatryRequest $request)
    {
        $user =  \Auth::user();

        if (isset($user->inflCode) && $user->inflCode != '') {
            $request['inflCode'] = $user->inflCode;
        }

        $lymprtcatry = LymPrtCatry::create($request->all());

        if ($request->hasFile('prtCaImg')) {
            $realPath = 'lymprtcatry/' . $lymprtcatry->prtCatId;

            $resizeImages = $lymprtcatry->resizeImages($request->file('prtCaImg'), $realPath);

            $lymprtcatry->update([
                'prtCaImg' => $resizeImages['image']
            ]);
        }

        return LytLoginUsr::GetMessage(new LymPrtCatryResource($lymprtcatry), config('constants.messages.create_success'));
    }

    /**
     * Update LymPrtCatry
     * @param LymPrtCatryUpdateRequest $request
     * @param LymPrtCatry $lymprtcatry
     * @return LymPrtCatryResource
     */
    public function update(LymPrtCatryUpdateRequest $request, LymPrtCatry $lymprtcatry)
    {
        $data = $request->all();

        if ($request->hasFile('prtCaImg')) {
            $realPath = 'lymprtcatry/' . $lymprtcatry->prtCatId;

            $resizeImages = $lymprtcatry->resizeImages($request->file('prtCaImg'), $realPath);

            $lymprtcatry->update([
                'prtCaImg' => $resizeImages['image']
            ]);
        }

        $lymprtcatry->update($data);
        return LytLoginUsr::GetMessage(new LymPrtCatryResource($lymprtcatry), config('constants.messages.update_success'));
    }

    /**
     * Delete LymPrtCatry
     *
     * @param Request $request
     * @param LymPrtCatry $lymprtcatry
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, LymPrtCatry $lymprtcatry)
    {
        $lymprtcatry->delete();
        return LytLoginUsr::GetMessage(new LymPrtCatryResource($lymprtcatry), config('constants.messages.delete_success'));
    }

    /**
     * Delete LymPrtCatry multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        if (!empty($request->prtCatId)) {

            LymPrtCatry::noLock()->whereIn('prtCatId', $request->prtCatId)->get()->each(function ($lymprtcatry) {
                $lymprtcatry->delete();
            });
            return new DataTrueResource(true, config('constants.messages.delete_success'));
        } else {
            return LytLoginUsr::GetError(config('constants.messages.delete_multiple_error'));
        }
    }

    public function deleteImage(Request $request, LymPrtCatry $lymprtcatry)
    {
        $lymprtcatry = LymPrtCatry::noLock()->find($request->prtCatId);
        $realPath = 'lymprtcatry/' . $lymprtcatry->prtCatId . basename($lymprtcatry->prtCaImg);
        $lymprtcatry->deleteOne($realPath);
        $lymprtcatry->update(['prtCaImg' => NULL]);

        return new DataTrueResource(true, config('constants.messages.delete_image_success'));
    }
}
