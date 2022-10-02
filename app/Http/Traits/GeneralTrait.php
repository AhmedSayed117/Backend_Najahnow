<?php

namespace App\Http\Traits;

trait GeneralTrait
{

    public $headers = [
         'Access-Control-Allow-Origin' => '*',
         'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE',
         'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Access-Control-Allow-Origin, Access-Control-Allow-Methods',
];

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($errNum, $code, $msg)
    {
        return response()->json(
        [
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ],
            $code,
            $this->headers
        );
    }


    public function returnSuccessMessage($msg = "", $code = 200, $errNum = "S000")
    {
        return response()->json([
            'status' => true,
            'errNum' => $errNum,
            'msg' => $msg
        ], $code, $this->headers);
    }

    public function returnData($key, $value, $code = 200, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value
        ], $code, $this->headers);
    }

    public function returnDoubleData($key, $value, $key2, $value2, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value,
            $key2 => $value2
        ], 200, $this->headers);
    }


    //////////////////
    public function returnValidationError($code = "E001", $validator)
    {
        // dd($validator);
        return $this->returnError($code, 400, $validator->errors()->first());
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        return $this->getErrorCode($inputs[0]);
    }

    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else if ($input == "description")
            return 'E003';

        else if ($input == 'duration')
            return 'E004';

        else if ($input == 'gif')
            return 'E005';

        else if ($input == 'cal_burnt')
            return 'E006';

        else if ($input == 'title')
            return 'E007';

        else if ($input == 'reps')
            return 'E008';

        else if ($input == 'image')
            return 'E009';

        else if ($input == 'equipment_id')
            return 'E010';

        else if ($input == "link")
            return 'E011';

        else if ($input == "datetime")
            return 'E012';

        else if ($input == "id")
            return 'E013';

        else if ($input == "price")
            return 'E014';

        else if ($input == "doctor_id")
            return 'E015';

        else if ($input == "payment_method" || $input == "payment_method_id")
            return 'E016';

        else if ($input == "day_date")
            return 'E017';

        else if ($input == "specification_id")
            return 'E018';

        else if ($input == "importance")
            return 'E019';

        else if ($input == "type")
            return 'E020';

        else if ($input == "message")
            return 'E021';

        else if ($input == "reservation_no")
            return 'E022';

        else if ($input == "reason")
            return 'E023';

        else if ($input == "branch_no")
            return 'E024';

        else if ($input == "name_en")
            return 'E025';

        else if ($input == "name_ar")
            return 'E026';

        else if ($input == "gender")
            return 'E027';

        else if ($input == "nickname_en")
            return 'E028';

        else if ($input == "nickname_ar")
            return 'E029';

        else if ($input == "rate")
            return 'E030';

        else if ($input == "price")
            return 'E031';

        else if ($input == "information_en")
            return 'E032';

        else if ($input == "information_ar")
            return 'E033';

        else if ($input == "street")
            return 'E034';

        else if ($input == "branch_id")
            return 'E035';

        else if ($input == "insurance_companies")
            return 'E036';

        else if ($input == "photo")
            return 'E037';

        else if ($input == "logo")
            return 'E038';

        else if ($input == "working_days")
            return 'E039';

        else if ($input == "insurance_companies")
            return 'E040';

        else if ($input == "reservation_period")
            return 'E041';

        else if ($input == "nationality_id")
            return 'E042';

        else if ($input == "commercial_no")
            return 'E043';

        else if ($input == "nickname_id")
            return 'E044';

        else if ($input == "reservation_id")
            return 'E045';

        else if ($input == "attachments")
            return 'E046';

        else if ($input == "summary")
            return 'E047';

        else if ($input == "user_id")
            return 'E048';

        else if ($input == "mobile_id")
            return 'E049';

        else if ($input == "paid")
            return 'E050';

        else if ($input == "use_insurance")
            return 'E051';

        else if ($input == "doctor_rate")
            return 'E052';

        else if ($input == "provider_rate")
            return 'E053';

        else if ($input == "message_id")
            return 'E054';

        else if ($input == "hide")
            return 'E055';

        else if ($input == "checkoutId")
            return 'E056';

        else
            return "";
        switch ($input) {
            case 'item not found':
                return "E401";
                break;
            case 'plan not found':
                return "E402";
                break;
            case 'meal not found':
                return "E403";
                break;
            case 'member not found':
                return "E404";
                break;
            case 'item exists':
                return "E602";
                break;
            case 'meal exists':
                return "E603";
                break;
            case 'title':
                return "E502";
                break;
            case 'cal':
                return "E503";
                break;
            case 'level':
                return "E504";
                break;
            case 'description':
                return "E505";
                break;
            case 'time':
                return "E506";
                break;
            case 'day':
                return "E507";
                break;
            case 'quantity':
                return "E508";
            case 'itemId':
                return "E509";
                break;
            case 'plan_item_id':
                return "E510";
                break;
            case 'mealId':
                return "E511";
                break;
            case 'type':
                return "E512";
                break;
            case 'plan_meal_id':
                return "E513";
                break;
            case 'duration':
                return "E514";
                break;
            case 'plan_id':
                return "E515";
                break;
            case 'fitness summary not found':
                return "E516";
                break;
            default:
                return "E500";
                break;
        }
    }
}
