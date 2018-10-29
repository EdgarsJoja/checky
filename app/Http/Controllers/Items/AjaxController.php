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
        $data = $request->get('data');
        $response = [
            'success' => false,
            'message' => '',
            'data' => []
        ];

        $item = new Item($data);

        try {
            if ($item->validate()) {
                $item->save();

                $response['success'] = true;
                $response['message'] = 'Item saved';
                $response['data']['id'] = $item->id;
            }
        } catch (Exception $e) {
            $response['message'] = 'Item could not be saved';
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
        $data = $request->post();
        $response = [
            'success' => false,
            'message' => ''
        ];

        if (isset($data['id'])) {
            $item = Item::find($data['id']);

            try {
                $item->fill($data);

                if ($item->validate()) {
                    $item->save();

                    $response['success'] = true;
                    $response['message'] = 'Item updated';
                }
            } catch (Exception $e) {
                $response['message'] = 'Item could not be updated';
            }
        } else {
            $response['message'] = 'Item ID not specified';
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
        $data = $request->post();
        $response = [
            'success' => false,
            'message' => ''
        ];

        if (isset($data['id'])) {
            $item = Item::find($data['id']);

            try {
                $item->delete();

                $response['success'] = true;
                $response['message'] = 'Item deleted';
            } catch (Exception $e) {
                $response['message'] = 'Item could not be deleted';
            }
        } else {
            $response['message'] = 'Item ID not specified';
        }

        return json_encode($response);
    }
}
