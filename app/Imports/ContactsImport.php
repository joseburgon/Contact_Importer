<?php

namespace App\Imports;

use App\Contact;
use App\Rules\Card;
use App\Rules\UniqueEmail;
use Inacho\CreditCard;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;


class ContactsImport implements ToModel, WithStartRow, WithValidation
{

    use Importable, SkipsFailures;

    public function __construct(array $fields, int $startRow)
    {
        $this->fields = $fields;
        $this->startRow = $startRow;
        $this->user_id = Auth::id();
    }

    public function startRow(): int
    {
        return $this->startRow;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        //dd($row, $this->fields['name'], $this->fields['email']);
        $card = CreditCard::validCreditCard($row[$this->fields['card']]);



        return new Contact([
            'user_id' => $this->user_id,
            'name' => $row[$this->fields['name']],
            'birthday' => $row[$this->fields['birthday']],
            'phone' => $row[$this->fields['phone']],
            'address' => $row[$this->fields['address']],
            'card' => $row[$this->fields['card']],
            'card_brand' => $card['type'],
            'email' => $row[$this->fields['email']],
        ]);
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function rules(): array
    {
        return [
            $this->fields['name'] => ['required', 'regex:/^[_A-z]*((-|\s)*[_A-z])*$/'],
            $this->fields['birthday'] => 'required|date',
            $this->fields['phone'] => ['required', 'regex:/(^[(][+]\d{1,2}[)]\s\d{3}( |-)\d{3}( |-)\d{2}( |-)\d{2}$)/'],
            $this->fields['address'] => 'required|string',
            $this->fields['card'] => ['required', new Card],
            $this->fields['email'] =>['required', 'email', new UniqueEmail($this->user_id)],
        ];
        //'regex:^[a-zA-Z0-9 -]*$'
    }

    public function customValidationMessages()
    {
        return [
            $this->fields['name'] . '.regex' => 'Name contains special characters.',
            $this->fields['phone'] . '.regex' => 'Invalid phone format.',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            $this->fields['name'] => 'name',
            $this->fields['birthday'] => 'birthday',
            $this->fields['phone'] => 'phone',
            $this->fields['address'] => 'address',
            $this->fields['card'] => 'card',
            $this->fields['email'] => 'email',
        ];
    }
}
