<?php

namespace Larazoul\Larazoul\Traits;

trait ApiTrait
{

    protected $status_code = 200;
    protected $successStatsCodeArray = [
        '200'
    ];

    protected function apiResponse($data = null , $error = null){

        return response(
            [
                'data' => $data ,
                'status' => $this->returnStatus(),
                'error' => $error
            ] ,
            $this->status_code
        );

    }

    /*
     * validation response error
     */

    protected function validateResponseError($error){
        $this->status_code = 422;
        return $this->apiResponse(null , $error->errors());
    }

    /*
     * return not found this id
     */

    protected function itemNotFoundResponseError(){
        $this->status_code = 404;
        return $this->apiResponse(null , trans("larazoul::larazoul.not found you want to play !"));
    }


    /*
     * return status true only when status code
     * is on the success array status code
     * other wise return false
     */

    protected function returnStatus(){
        return in_array($this->status_code , $this->successStatsCodeArray)  ? true : false;
    }




}