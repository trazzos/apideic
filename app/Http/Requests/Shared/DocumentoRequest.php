<?php

namespace App\Http\Requests\Shared;

use Illuminate\Foundation\Http\FormRequest;

class DocumentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
        {
            return [
                'archivo' => [
                    'required',
                    'file',
                    'mimes:pdf,jpg,png,gif,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
                    'max:10240' // 10MB máximo
                ],
                'tipo_documento_id' => [
                    'nullable',
                    'integer',
                    'exists:tipos_documento,id'
                ]
            ];
        }

        /**
         * Get custom messages for validator errors.
         *
         * @return array
         */
        public function messages(): array
        {
            return [
                'documento.required' => 'Debe seleccionar un archivo',
                'documento.file' => 'El documento debe ser un archivo válido',
                'documento.mimes' => 'El documento debe ser de tipo: pdf, jpg, png, gif, doc, docx, xls, xlsx, ppt, pptx, txt, zip o rar',
                'documento.max' => 'El documento no debe pesar más de 10MB',
                'tipo_documento_id.exists' => 'El tipo de documento seleccionado no es válido'
            ];
        }
    }
