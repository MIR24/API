<?php

namespace App\Http\Controllers;

use App\Mirreport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{

    public function upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'name' => 'required',
            'profile' => 'required',

        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('title')) {
                throw new \HttpException('Title not specified.', 400);
            }
            if ($validator->errors()->has(['desc', 'name', 'profile'])) {
                throw new \HttpException('You must specify description, name and profile and send file', 400);
            }
        }

        if ($request->hasHeader('multipart/form-data')) {

            return $this->uploadFile($request);

        } elseif ($request->hasHeader('application/x-www-form-urlencoded')) {

            return $this->uploadData($request);
        } else {
            //TODO insert error
            throw new \HttpException("Not found headers", 418);
        }
    }


    public function uploadFile(Request $request)
    {


    }

    public function uploadData(Request $request)
    {
        /**
         *      if (!isMultipart) {
         * uploadRequest.setToken(request.getParameter("token"));
         * uploadRequest.setTitle(request.getParameter("title"));
         * String desc = request.getParameter("desc");
         * if (desc != null) {
         * uploadRequest.setDesc(desc);
         * }
         * uploadRequest.setName(request.getParameter("name"));
         * uploadRequest.setProfile(request.getParameter("profile"));
         * String email = request.getParameter("email");
         * if (email != null) {
         * uploadRequest.setEmail(email);
         * }
         * uploadRequest.setFileLoaded(Boolean.FALSE)
         */


        try {

            $mirreport = new Mirreport(
                [
                    'name' => $request->get('name'),
                    'profile' => $request->get('profile'),
                    'title' => $request->get('title'),
                    //  'filename' => $request->get('filename'),
                    'desc' => $request->get('desc'),
                    'email' => $request->get('email'),
                    ///  'date'=>''
                ]
            );

            $mirreport->save();
        } catch (\Exception $ex) {

        }

        return response('Your upload was submitted successfully.')->json();
    }
}
