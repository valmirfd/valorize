<?php

namespace Config;

use App\Validations\ChurchValidation;
use App\Validations\Customized;
use App\Validations\IgrejaValidation;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        Customized::class, // nossas validações
        IgrejaValidation::class, // nossas validações
        ChurchValidation::class, // nossas validações
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Igreja
    //--------------------------------------------------------------------

    public $igreja = [
        'id'          => 'permit_empty|is_natural_no_zero',
        'nome'        => 'required|min_length[3]|max_length[120]',
        'telefone'    => 'required|validate_phone|exact_length[15]|is_unique[igrejas.telefone,id,{id}]',
        'cnpj'        => 'required|validate_cnpj|exact_length[18]|is_unique[igrejas.cnpj,id,{id}]',
        'situacao'    => 'required',
        'is_sede'     => 'required',
    ];

    public $igreja_errors = [
        'nome' => [
            'required'       => 'Digite o nome da Igreja',
            'min_length'     => 'O nome dever pelo menos 3 caractéres',
            'max_length'     => 'O nome dever no máximo 120 caractéres',
        ],
        'telefone' => [
            'required'      => 'Digite o telefone',
            'exact_length'  => 'O telefone deve ter exatamente 15 caractéres. Ex.: (00) 98888-8888',
            'is_unique'     => 'Este telefone já está cadastrado.',
        ],
        'cnpj' => [
            'required'      => 'Digite o CNPJ da Igreja',
            'exact_length'  => 'O CNPJ deve ter exatamente 18 caractéres. ex.: 00.000.000/0000-00',
            'is_unique'     => 'Este CNPJ já está cadastrado.',
        ],
        'situacao' => [
            'required'     => 'Digite a situação [sede|filial]',
        ],
        'is_sede' => [
            'required'     => 'Igreja Sede[1:sim-0:não]',
        ],


    ];

    public $igreja_images = [
        'images' => 'uploaded[images]'
            . '|is_image[images]'
            . '|mime_in[images,image/jpg,image/jpeg,image/png,image/webp]'
            . '|max_size[images,2048]'
            . '|max_dims[images,1920,1200]',
    ];

    public $igreja_images_errors = [];
}
