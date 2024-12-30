<?php

namespace App\Services;

use App\Http\Helpers\General\Helper;

class GeneralService
{

    public function manageAttachment($imageData)
    {

        if (!is_dir("uploadfiles/" . $imageData['folder_name'])) {
            mkdir("uploadfiles/" . $imageData['folder_name'], 0777, true);
        }
        if ($file = $imageData['data']->file($imageData['request_name'])) {
//            $name = $imageData['file_name'] . '-' . time() . '.' . $file->clientExtension();
            $name = $this->removeSpaces($file->getClientOriginalName());

            $file->move('uploadfiles/' . $imageData['folder_name'], $name);
            $imageData['data']->request->add([$imageData['request_name_new'] => "uploadfiles/" . $imageData['folder_name'] . '/' . $name]);
        }
        if ($imageData['returnType'] == 'request') {
            return $imageData['data'];
        } else {
            return "uploadfiles/" . $imageData['folder_name'] . '/' . $name;
        }
    }
    public function removeSpaces($text, $replaceWith='-')
    {

        $slug = str_replace(" ", $replaceWith, $text);
        return $slug;
    }

    public function nameToSlug($request, $name)
    {

        $slug = str_replace(" ", "-", $request->$name);
        $slug = strtolower($slug);
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $slug);

        $request->request->add(['slug' => $slug]);
        return $request;
    }

    public function verifySlug($model, $slug, $exceptId = null)
    {
        $get = $model::where('slug', $slug);
        if ($exceptId != null) {
            $get = $get->where('id', '!=', $exceptId);
        }
        $get = $get->count();
        if ($get == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function removeSpecialChar($str)
    {
        $res = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $str);
        return $res;
    }

    public function handleException($e,$route=false)
    {
        if ($route){
            return redirect()->route($route)->with('error','Something went wrong, Please try again later');
        }
        return null;
    }

    public function clear_cache_cloudflare(){}

}
