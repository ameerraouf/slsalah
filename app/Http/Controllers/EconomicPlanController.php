<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Workspace;
use Illuminate\Support\Str;
use App\Models\SwotAnalysis;
use Illuminate\Http\Request;
use App\Models\PestelAnalysis;
use App\Http\Controllers\Controller;
use App\Models\PorterModel;
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

        $this->porterAnalysis($request);
        $this->swotAnalysis($request);
        $this->pestelAnalysis($request);
    }

    private function pestelAnalysis($request)
    {
        $pestel_message = "I want to write a pestel analysis based on the answers of the following questions . <br />
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

                 <br />
                The answer must have each factor of the following on a new line but with same representation and style<br />
                political factors , economic factors , social factors , technological factors . and enviromental factors and legal factors
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
                    'content' => $pestel_message,
                ],
            ],
            "model" => 'gpt-3.5-turbo'
        ];
        $response = Http::withHeaders([
            'Authorization' => "Bearer " .  json_decode($api_keys)[1],
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', $payload);
        $responseData = json_decode($response, true);
        $message = $responseData['choices'][0]['message']['content'];
        $pestelText = trim($message);

        // Initialize an empty array to store the factors and their descriptions
        $factors = array();

        // Extract factors using regular expressions
        $pattern = '/([A-Za-z ]+ factors):\s*\n((?:- .+\n)+)/';
        preg_match_all($pattern, $pestelText, $matches, PREG_SET_ORDER);

        // Iterate through the matched factors and their descriptions
        foreach ($matches as $match) {
            $factorKey = trim($match[1]); // Remove leading/trailing whitespace
            $factorText = trim($match[2]); // Remove leading/trailing whitespace
            $descriptions = explode("\n- ", $factorText);
            array_shift($descriptions); // Remove empty first element
            $factors[$factorKey] = $descriptions;
        }
        $final_factors = [];
        foreach ($factors as $key => $value) {
            if (str_contains($key, 'Political')) {
                $final_factors['Political Factors'] = $value;
            }
            if (str_contains($key, 'Economic')) {
                $final_factors['Economic Factors'] = $value;
            }
            if (str_contains($key, 'Social')) {
                $final_factors['Social Factors'] = $value;
            }
            if (str_contains($key, 'Technological')) {
                $final_factors['Technological Factors'] = $value;
            }
            if (str_contains($key, 'Environmental')) {
                $final_factors['Environmental Factors'] = $value;
            }
            if (str_contains($key, 'Legal')) {
                $final_factors['Legal Factors'] = $value;
            }
        }
        // write the swot analysis 
        $pestel_analysis = PestelAnalysis::create([
            "uuid" => Str::uuid(),
            "workspace_id" => auth()->user()->workspace_id,
            "admin_id" => 0,
            "company_name" => $settings['company_name'],
            "political" => json_encode($final_factors['Political Factors']) ?? [],
            "economic" => json_encode($final_factors['Economic Factors']) ?? [],
            "social" => json_encode($final_factors['Social Factors']) ?? [],
            "technological" => json_encode($final_factors['Technological Factors']) ?? [],
            "environmental" => json_encode($final_factors['Environmental Factors']) ?? [],
            "legal" => json_encode($final_factors['Legal Factors']) ?? [],
        ]);
    }
    private function swotAnalysis($request)
    {
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


        // Regular expression pattern to extract threats
        if (preg_match('/Threats:(.*?)(?=Strengths:|Weaknesses:|Opportunities:|$)/s', $message, $matches)) {
            $threatLines = explode(PHP_EOL, $matches[1]);
            $threatLines = array_map('trim', $threatLines);
            $threatLines = array_filter($threatLines);
            $threats = array_values($threatLines);
        }

        // Output the extracted threats
        foreach ($threats as $threat) {
            $threats = array_merge($threats, preg_split('/\R/', $threat));
        }
        $threats = array_map('trim', $threats);

        // Remove any empty elements
        $threats = array_filter($threats);

        $swot_data['threats'] = $threats;

        // Regular expression pattern to extract opportunities
        if (preg_match('/Opportunities:(.*?)(?=Strengths:|Weaknesses:|Threats:|$)/s', $message, $matches)) {
            $opportunitLines = explode(PHP_EOL, $matches[1]);
            $opportunitLines = array_map('trim', $opportunitLines);
            $opportunitLines = array_filter($opportunitLines);
            $opportunities = array_values($opportunitLines);
        }

        // Output the extracted threats
        foreach ($opportunities as $Opportunity) {
            $opportunities = array_merge($opportunities, preg_split('/\R/', $Opportunity));
        }
        $opportunities = array_map('trim', $opportunities);
        // Remove any empty elements
        $opportunities = array_filter($opportunities);
        $swot_data['opportunities'] = $opportunities;


        // Regular expression pattern to extract strengths
        if (preg_match('/Strengths:(.*?)(?=Opportunities:|Weaknesses:|Threats:|$)/s', $message, $matches)) {
            $StrengthsLines = explode(PHP_EOL, $matches[1]);
            $StrengthsLines = array_map('trim', $StrengthsLines);
            $StrengthsLines = array_filter($StrengthsLines);
            $strengths = array_values($StrengthsLines);
        }

        // Output the extracted threats
        foreach ($strengths as $strength) {
            $strengths = array_merge($strengths, preg_split('/\R/', $strength));
        }
        $strengths = array_map('trim', $strengths);

        // Remove any empty elements
        $strengths = array_filter($strengths);
        $swot_data['strengths'] = $strengths;



        // Regular expression pattern to extract weaknesses
        if (preg_match('/Weaknesses:(.*?)(?=Opportunities:|Strengths:|Threats:|$)/s', $message, $matches)) {
            $WeaknessesLines = explode(PHP_EOL, $matches[1]);
            $WeaknessesLines = array_map('trim', $WeaknessesLines);
            $WeaknessesLines = array_filter($WeaknessesLines);
            $weaknesses = array_values($WeaknessesLines);
        }

        // Output the extracted threats
        foreach ($weaknesses as $weaknesse) {
            $weaknesses = array_merge($weaknesses, preg_split('/\R/', $weaknesse));
        }
        $weaknesses = array_map('trim', $weaknesses);

        // Remove any empty elements
        $weaknesses = array_filter($weaknesses);
        $swot_data['weaknesses'] = $weaknesses;


        // write the swot analysis 
        $swot_analysis = SwotAnalysis::create([
            "uuid" => Str::uuid(),
            "workspace_id" => auth()->user()->workspace_id,
            "admin_id" => 0,
            "company_name" => $settings['company_name'],
            "strengths" => json_encode($swot_data['strengths']),
            "weaknesses" => json_encode($swot_data['weaknesses']),
            "opportunities" => json_encode($swot_data['opportunities']),
            "threats" => json_encode($swot_data['threats']),
        ]);
    }

    private function porterAnalysis($request)
    {
        $pestel_message = "I want to write a porter-5 analysis based on the answers of the following questions . <br />
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

                include the following words as the forces in the answer and please make sure that they are present in the text in the same style and syntax and make sure each force is on a new line <br />
                entrants , rivals , suppliers , customers , substitute <br />
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
                    'content' => $pestel_message,
                ],
            ],
            "model" => 'gpt-3.5-turbo'
        ];
        $response = Http::withHeaders([
            'Authorization' => "Bearer " .  json_decode($api_keys)[1],
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', $payload);
        $responseData = json_decode($response, true);
        $message = $responseData['choices'][0]['message']['content'];
        // return $message;
        $text = trim($message);

        // Initialize an empty array to store the Porter's Five Forces analysis
        $forces = array();

        // Extract forces using regular expressions
        // Extract force names using regular expressions
        $pattern = "/(\d+\.[^\n:]+):\n((?:.+\n)+)/";
        preg_match_all($pattern, $text, $matches);

        // Remove leading numbers and whitespace from force names
        $forces = array_map(function ($match) {
            return trim(preg_replace("/^\d+\.\s*/", '', $match));
        }, $matches[1]);

        // Extract force descriptions
        $descriptions = $matches[2];

        // Create an array with force names as keys and descriptions as values
        $forcesArray = array_combine($forces, $descriptions);
        // return $forcesArray;
        foreach ($forcesArray as $key => $value) {
            if (str_contains($key, 'Rivals') || str_contains($key, 'Rivalary') || str_contains($key, 'Rivalry')) {
                $final_forces['Rivals'] = $value;
            }
            if (str_contains($key, 'Entrants')) {
                $final_forces['Entrants'] = $value;
            }
            if (str_contains($key, 'Suppliers')) {
                $final_forces['Suppliers'] = $value;
            }
            if (str_contains($key, 'Customers')) {
                $final_forces['Customers'] = $value;
            }
            if (str_contains($key, 'Substitute')) {
                $final_forces['Substitute'] = $value;
            }
        }
        // return $final_forces;
        // write the swot analysis 
        $porter_analysis = PorterModel::create([
            "uuid" => Str::uuid(),
            "workspace_id" => auth()->user()->workspace_id,
            "admin_id" => 0,
            "company_name" => $settings['company_name'],
            "rivals" => ($final_forces['Rivals']) ?? null,
            "entrants" => ($final_forces['Entrants']) ?? null,
            "suppliers" => ($final_forces['Suppliers']) ?? null,
            "customers" => ($final_forces['Customers']) ?? null,
            "substitute" => ($final_forces['Substitute']) ?? null,
        ]);
        return $message;
    }
}
