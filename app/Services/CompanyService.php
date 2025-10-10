<?php

namespace App\Services;

use App\Models\CompanyModel;
use CodeIgniter\I18n\Time;

class CompanyService
{
    private CompanyModel $companyModel;
    private $user;

    public function __construct()
    {
        $this->companyModel = model(CompanyModel::class);
        $this->user = auth()->user();
    }

    public function listarCompanies(): array|null
    {
        $companies = $this->companyModel->where('user_id', $this->user->id)->orderBy('id', 'DESC')->findAll();

        $data = [];

        if ($companies === null || $companies === []) {
            return null;
        }

        foreach ($companies as $company) {

            $criado = date_format($company->created_at, 'd/m/Y');
            $editado = date_format($company->updated_at, 'd/m/Y');

            $data[] = [
                "name"       => $company->name,
                "phone"      => $company->phone,
                "email"      => $company->email,
                "address"    => $company->address,
                "created_at" => $criado,
                "updated_at" => $editado,
            ];
        }

        return $data;

        //return $companies;
    }
}
