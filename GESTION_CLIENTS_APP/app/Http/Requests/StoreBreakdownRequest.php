<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBreakdownRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // L'utilisateur authentifié peut déclarer une panne
        return auth()->check();
    }

    /**
     * Récupère les règles de validation qui s'appliquent à la requête.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicule_id' => [
                'required',
                'exists:vehicules,id',
                // Vérifier que le véhicule appartient à l'utilisateur authentifié
                function ($attribute, $value, $fail) {
                    $vehicule = \App\Models\Vehicule::find($value);
                    
                    if (!$vehicule || $vehicule->user_id !== auth()->id()) {
                        $fail('Le véhicule sélectionné ne vous appartient pas.');
                    }
                },
            ],
            'description' => [
                'required',
                'string',
                'min:20',
                'max:1000'
            ],
            'onsite_assistance' => [
                'required',
                'boolean'
            ],
            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
                function ($attribute, $value, $fail) {
                    // Si dépannage sur place, latitude est requise
                    if ($this->input('onsite_assistance') && !$value) {
                        $fail('La latitude est requise pour un dépannage sur place.');
                    }
                }
            ],
            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
                function ($attribute, $value, $fail) {
                    // Si dépannage sur place, longitude est requise
                    if ($this->input('onsite_assistance') && !$value) {
                        $fail('La longitude est requise pour un dépannage sur place.');
                    }
                }
            ],
            'technicien_id' => [
                'nullable',
                'exists:techniciens,id'
            ]
        ];
    }

    /**
     * Récupère les messages d'erreur personnalisés pour les règles de validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'vehicule_id.required' => 'Le véhicule est obligatoire.',
            'vehicule_id.exists' => 'Le véhicule sélectionné n\'existe pas.',
            'description.required' => 'La description de la panne est obligatoire.',
            'description.min' => 'La description doit contenir au moins 20 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'onsite_assistance.required' => 'Veuillez spécifier si vous avez besoin d\'un dépannage sur place.',
            'onsite_assistance.boolean' => 'La valeur du dépannage sur place doit être valide.',
            'latitude.numeric' => 'La latitude doit être un nombre.',
            'latitude.between' => 'La latitude doit être entre -90 et 90.',
            'longitude.numeric' => 'La longitude doit être un nombre.',
            'longitude.between' => 'La longitude doit être entre -180 et 180.',
            'technicien_id.exists' => 'Le technicien sélectionné n\'existe pas.'
        ];
    }

    /**
     * Prépare les données pour la validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Convertir onsite_assistance en booléen
        $this->merge([
            'onsite_assistance' => $this->input('onsite_assistance') === 'true' || $this->input('onsite_assistance') === true,
        ]);
    }
}
