<?php

namespace App\Http\Helpers\General;


use App\Models\Activity;
use App\Models\Specification;
use App\Models\SpecificationOption;
use App\Models\State;
use App\Models\Tag;
use App\Models\UserQuizAnswer;
use App\Services\GeneralService;
use App\Services\PostService;
use App\Services\WebService;
use Carbon\Carbon;
use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\Auth;
use App\Models\Chapter;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\Self_;
use function PHPUnit\Framework\fileExists;

class Helper{

    /**
     * This function will add ellipsis in a string after given Limit
     * @param $str
     * @param $limit
     * @param string $end
     * @return string
     */
    public static function ellipsis($str, $limit=null, $end='...',$stripHTML=false){
        if(!$limit)
            $limit = config('settings.text_limit');

        if($stripHTML)
            $str = strip_tags($str);

        return \Illuminate\Support\Str::limit($str, $limit = $limit, $end = $end);
    }

    public static function errorHandling($exception,$request){
        //            $data = ['message' => $exception->getMessage()];
//            $mail = Mail::to(config('settings.map.dev_email'))->send(new TestEmail($data));
    }

    public static function nameToUrl($name){
        $url = strtolower($name);
        $url = str_replace(' ','-',$url);
        $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
       return $url;
    }

    public static function DisplaySlug($slug,$type = 'post'){
        if ($slug == null) return '';
        if ($type == 'post'){
            $slug = explode('-',$slug);
            unset($slug[count($slug)-1]);
            $slug = implode('-',$slug);
        }elseif ('type'){
            $slug = explode('-',$slug);
            $capitalize = [];
            foreach ($slug as $s){
                $s = ucfirst($s);
                $capitalize[] = $s;
            }
            $slug = implode(' ',$capitalize);
        }

        return $slug;

    }

    public static function convertDateFormat($date,$convertTo){
        $date = Carbon::parse($date);

        if($convertTo == '24'){
            return $date->format('Y-m-d H:i:s');
        }
        return $date->format('Y-m-d h:i:s A');
//        return $date->format('m-d-Y h:m:s A');
    }

    public static function cleanText($text){
        return strip_tags($text);
    }

    public static function makeArray($val){
        if (is_array($val)){
            return $val;
        }
        return [$val];
    }

    public static function isJson($string) {
        if (is_object(json_decode($string))){
            return true;
        }
        return  false;
    }

    public static function removeHtmlTags($string){

        $withoutImgTags = preg_replace('/<img[^>]*>/', '', $string);
        $cleanedString = strip_tags($withoutImgTags);

        return $cleanedString;
    }




}
