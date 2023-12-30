<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class EconomicPlanController extends Controller
{
    public function index()
    {
        return view('economic-plans.index', [
            "selected_navigation" => "economic-plan",
        ]);
    }
    public function create(Request $request)
    {
        $request->validate([
            "industry" => 'required',
            "audience" => 'required',
            "business_size" => 'required',
            "product_nature" => 'required',
            "tech_focus" => 'required',
            "market_position" => 'required',
            "location" => 'required',
        ], [
            "industry.required'" => 'حقل الصناعة مطلوب',
            "audience.required'" => 'حقل الجمهور المستهدف مطلوب',
            "business_size.required'" => 'حقل حجم العمل مطلوب ',
            "product_nature.required'" => 'حقل طبيعة المنتج مطلوب',
            "tech_focus.required'" => 'حقل التركيز التكنولوجي مطلوب',
            "market_position.required'" => 'حقل موقع السوق الرئيسي مطلوب',
            "location.required'" => 'حقل المناطق الجغرافية مطلوب',
        ]);

        $swot_message = "I want to write a swot analysis based on the answers of the following questions . <br />
                question 1 : Choose an industry for your project or business <br/>
                answer for question 1 : {$request->industry} <br/>
                question 2 : Specify the size of your business/project. <br/>
                answer for question 2 : {$request->business_size} <br/>
                question 3 : Specify the primary target audience for your project/business. <br/>
                answer for question 3 : {$request->audience} <br/>
                question 4 : Specify the nature of your product/service. <br/>
                answer for question 4 : {$request->product_nature} <br/>
                question 5 : Choose the primary technological focus for your project/business. <br/>
                answer for question 5 : {$request->tech_focus} <br/>
                question 6 : Specify the primary market location for your project/business. <br/>
                answer for question 6 : {$request->market_position} <br/>
                question 7 : Choose the geographical regions in which your project/business operates. <br/>
                answer for question 7 : {$request->location} <br/>
        ";

        $workspace = Workspace::find(1);
        $settings_data = Setting::where('workspace_id', $workspace->id)->get();
        $settings = [];
        foreach ($settings_data as $setting) {
            $settings[$setting->key] = $setting->value;
        }

        if (array_key_exists('api_keys', $settings)) {
            $api_keys = $settings['api_keys'];
        } else {
            throw ValidationException::withMessages([
                'api_keys' => 'does not exist',
            ]);
        }

        // send to openai api 
        $payload = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You can start the conversation.',
                ],
                [
                    'role' => 'user',
                    'content' => $swot_message,
                ],
            ],
            "model" => 'gpt-3.5-turbo'
        ];
        $response = Http::withHeaders([
            'Authorization' => "Bearer " .  json_decode($api_keys)[0],
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', $payload);
        $responseData = json_decode($response, true);
        $message = $responseData['choices'][0]['message']['content'];
        return $message;
    }
}
