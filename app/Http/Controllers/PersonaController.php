<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PersonaController extends Controller
{
    public function show(Request $request)
    {
        $response = $this->getData($request->personCount = 5);
        $data = json_decode(json_encode($response->json()));
        return $this->mostUsedLetter($data->results);
    }

    private function getData($personCount)
    {
        return Http::get('https://randomuser.me/api',['results' => $personCount]);
    }

    private function mostUsedLetter($results)
    {
        $persons = [];

        foreach ($results as $person) {
            $p = $person;
            $fullName = "{$person->name->title} {$person->name->first} {$person->name->last}";
            $p->fullName = $fullName;

            $fullNameWithoutWhitespace  = str_replace(' ', '', $fullName);
            $characters = array_count_values(str_split($fullNameWithoutWhitespace));
            arsort($characters);
            $person->characters = $characters;
            $person->mostUsedLetter = [array_key_first($characters) => $characters[array_key_first($characters)]];

            array_push($persons, $p);
        }
        return $persons;
    }
}
