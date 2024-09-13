<?php

namespace RiseTech\FormRequest;

class Rules
{
    public static function default(): array
    {
        $form = config('rules.form') ?? [];

        return array_merge($form,
            [
                'form_request' => [
                    'form' => 'bail|required|min:3|unique:form_requests,form|isName',
                    'rules' => 'bail|required|array',
                ]
            ]
        );
    }
}
