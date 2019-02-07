<?php

namespace App\Http\Controllers\Items;

use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;

class AjaxController extends Controller
{
    /**
     * Save item action
     *
     * @param Request $request
     * @return string
     */
    public function save(Request $request)
    {
        $data = $request->get('data') ?: json_decode($request->getContent(), true);
        $response = [
            'success' => false,
            'message' => [],
            'data' => []
        ];

        try {
            foreach ($data as $itemData) {

                $item = new Item($itemData);

                if ($item->validate()) {
                    $item->save();

                    $response['success'] = true;
                    $response['message'][] = 'Item saved';
                    $response['data']['ids'][] = $item->id;
                }
            }
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = 'Item(-s) could not be saved';
        }

        return json_encode($response);
    }

    /**
     * Update item action
     *
     * @param Request $request
     * @return string
     */
    public function update(Request $request)
    {
        $data = $request->post() ?: json_decode($request->getContent(), true);
        $response = [
            'success' => false,
            'message' => []
        ];

        try {
            foreach ($data as $itemData) {
                $item = Item::firstOrNew([
                    'uuid' => $itemData['uuid']
                ]);

                $item->fill($itemData);

                if ($item->validate()) {
                    $item->save();

                    $response['message'][] = 'Item updated';
                } else {
                    $response['message'][] = 'Item data not valid';
                }
            }

            $response['success'] = true;
        } catch (Exception $e) {
            $response['message'][] = 'Item(-s) could not be updated';
        }

        return json_encode($response);
    }

    /**
     * Update item action
     *
     * @param Request $request
     * @return string
     */
    public function delete(Request $request)
    {
        $data = $request->post() ?: json_decode($request->getContent(), true);
        $response = [
            'success' => false,
            'message' => []
        ];

        if (isset($data['uuid'])) {
            $item = Item::where(['uuid' => $data['uuid']])->firstOrFail();

            try {
                $item->delete();

                $response['success'] = true;
                $response['message'][] = 'Item deleted';
            } catch (Exception $e) {
                $response['message'][] = 'Item could not be deleted';
            }
        } else {
            $response['message'][] = 'Item ID not specified';
        }

        return json_encode($response);
    }

    /**
     * Return user items
     *
     * @param Request $request
     * @return false|string
     */
    public function get(Request $request)
    {
        return json_encode($this->getItems());
    }
}
